<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Visitor;
use App\Models\VisitorEvent;
use App\Models\WhatsappChatImport;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class WhatsappChatImportController extends Controller
{
    private const MAX_ARCHIVE_KB = 20480;
    private const MAX_TRANSCRIPT_BYTES = 10485760;

    public function store(Request $request)
    {
        $data = $request->validate([
            'chat_zip' => ['required', 'file', 'mimes:zip', 'max:' . self::MAX_ARCHIVE_KB],
        ]);

        if (!class_exists(ZipArchive::class)) {
            return back()->withErrors(['chat_zip' => 'El servidor no tiene habilitado el lector ZIP.']);
        }

        $zip = new ZipArchive();
        if ($zip->open($data['chat_zip']->getRealPath()) !== true) {
            return back()->withErrors(['chat_zip' => 'No se pudo abrir el ZIP.']);
        }

        try {
            $entries = [];
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $stat = $zip->statIndex($i);
                $name = $stat['name'] ?? '';
                if (str_ends_with($name, '/') || !preg_match('/\.(txt|md)$/i', $name)) {
                    continue;
                }

                $size = (int) ($stat['size'] ?? 0);
                if ($size > self::MAX_TRANSCRIPT_BYTES) {
                    return back()->withErrors(['chat_zip' => 'El archivo de conversación supera el límite de 10 MB.']);
                }
                $entries[] = ['index' => $i, 'name' => $name, 'size' => $size, 'extension' => strtolower(pathinfo($name, PATHINFO_EXTENSION))];
            }

            if (!$entries) {
                return back()->withErrors(['chat_zip' => 'El ZIP no contiene un archivo .txt o .md de conversación.']);
            }

            // WhatsApp puede incluir el mismo chat en TXT y Markdown: se importa solo uno.
            usort($entries, fn ($a, $b) => ($a['extension'] === 'txt' ? 0 : 1) <=> ($b['extension'] === 'txt' ? 0 : 1));
            $entry = $entries[0];
            $transcript = $zip->getFromIndex($entry['index']);
            if (!is_string($transcript) || trim($transcript) === '') {
                return back()->withErrors(['chat_zip' => 'El archivo de conversación está vacío o no se pudo leer.']);
            }
        } finally {
            $zip->close();
        }

        $transcript = mb_convert_encoding($transcript, 'UTF-8', 'UTF-8, Windows-1252, ISO-8859-1');
        $timestamps = $this->extractMessageTimestamps($transcript, $this->timestampFormatHint(basename($entry['name'])));
        $hash = hash('sha256', trim($transcript));
        $sourceName = basename($entry['name']);
        $identity = $this->extractContactIdentity($sourceName, $transcript);

        try {
            $chat = DB::transaction(function () use ($request, $hash, $sourceName, $identity, $transcript, $timestamps) {
                $chat = WhatsappChatImport::firstOrCreate(
                    ['content_hash' => $hash],
                    [
                        'source_name' => $sourceName,
                        'contact_name' => $identity['name'],
                        'phone' => $identity['phone'],
                        'transcript' => $transcript,
                        'message_timestamps' => $timestamps,
                        'message_count' => count($timestamps),
                        'first_message_at' => $timestamps ? min($timestamps) : null,
                        'last_message_at' => $timestamps ? max($timestamps) : null,
                        'imported_by' => $request->user()?->id,
                    ]
                );

                if (!$chat->lead_id) {
                    $norm = Lead::normalizePhone($identity['phone']);
                    $lead = $norm ? Lead::where('phone_norm', $norm)->first() : null;
                    $purchase = $norm ? Lead::findPurchase($norm, null) : null;
                    if (!$lead) {
                        $lead = Lead::create([
                            'name' => $identity['name'],
                            'phone' => $identity['phone'],
                            'nota' => 'Importado automáticamente desde chat de WhatsApp: ' . $sourceName . '. ' . count($timestamps) . ' mensajes con fecha detectada.',
                            'estado' => $purchase ? Lead::ESTADO_COMPRADO : Lead::ESTADO_NUEVO,
                            'source' => Lead::SOURCE_AUTO,
                            'invoice_id' => $purchase?->id,
                        ]);
                    } else {
                        $lead->update([
                            'name' => $lead->name ?: $identity['name'],
                            'phone' => $lead->phone ?: $identity['phone'],
                            'nota' => trim(($lead->nota ? $lead->nota . "\n" : '') . 'Chat WhatsApp importado: ' . $sourceName . '.'),
                            'estado' => $purchase ? Lead::ESTADO_COMPRADO : $lead->estado,
                            'invoice_id' => $purchase?->id ?: $lead->invoice_id,
                        ]);
                    }

                    $chat->update(['lead_id' => $lead->id]);
                }

                return $chat;
            });
        } catch (UniqueConstraintViolationException) {
            $chat = WhatsappChatImport::where('content_hash', $hash)->firstOrFail();
        }

        $created = $chat->wasRecentlyCreated;
        $message = $created
            ? 'Chat importado y lead creado automáticamente' . ($identity['name'] ? ' para ' . $identity['name'] : '') . '. ' . $chat->message_count . ' mensajes con fecha detectada.'
                : 'Este chat ya estaba importado; se conservó su lead y no se creó un duplicado.';

        return redirect()->route('admin.leads')->with('message', $message);
    }

    public function link(Request $request, WhatsappChatImport $chat)
    {
        $data = $request->validate([
            'visitor_id' => ['required', 'integer', 'exists:visitors,id'],
            'contact_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        $phone = trim($data['phone'] ?? '') ?: $chat->phone ?: null;
        $norm = Lead::normalizePhone($phone);
        if ($phone && !$norm) {
            return back()->withErrors(['phone' => 'Teléfono inválido.']);
        }

        $visitor = Visitor::findOrFail($data['visitor_id']);
        $nearestEvent = $this->nearestSiteEvent($chat, $visitor->id);
        if (!$nearestEvent) {
            return back()->withErrors(['visitor_id' => 'Ese visitante no tiene actividad de navegación cercana a la conversación.']);
        }

        $purchase = Lead::findPurchase($norm, $visitor->id);
        $name = trim($data['contact_name'] ?? '') ?: $chat->contact_name ?: $visitor->name;

        DB::transaction(function () use ($chat, $visitor, $nearestEvent, $name, $phone, $norm, $purchase) {
            $visitor->update(array_filter([
                'name' => $name ?: $visitor->name,
                'phone' => $phone ?: $visitor->phone,
            ]));

            $lead = $chat->lead ?: Lead::where('visitor_id', $visitor->id)->first();
            if (!$lead) {
                $lead = $norm ? Lead::where('phone_norm', $norm)->first() : null;
            }
            if (!$lead) {
                $lead = Lead::create([
                    'name' => $name,
                    'phone' => $phone,
                    'nota' => 'Vinculado desde chat de WhatsApp; actividad web: ' . $nearestEvent->type . ' (' . $nearestEvent->created_at->timezone('America/Costa_Rica')->format('d/m/Y H:i') . ').',
                    'source' => Lead::SOURCE_AUTO,
                    'visitor_id' => $visitor->id,
                    'invoice_id' => $purchase?->id,
                    'estado' => $purchase ? Lead::ESTADO_COMPRADO : Lead::ESTADO_NUEVO,
                ]);
            } else {
                $lead->update([
                    'name' => $name ?: $lead->name,
                    'phone' => $phone ?: $lead->phone,
                    'visitor_id' => $lead->visitor_id ?: $visitor->id,
                    'invoice_id' => $purchase?->id ?: $lead->invoice_id,
                    'estado' => $purchase ? Lead::ESTADO_COMPRADO : $lead->estado,
                ]);
            }

            $chat->update([
                'contact_name' => $name,
                'phone' => $phone,
                'visitor_id' => $visitor->id,
                'lead_id' => $lead->id,
            ]);
        });

        return redirect()->route('admin.leads')->with('message', 'Chat vinculado con el visitante y lead guardado.');
    }

    private function nearestSiteEvent(WhatsappChatImport $chat, int $visitorId): ?VisitorEvent
    {
        $timestamps = $chat->message_timestamps ?? [];
        if (!$timestamps) {
            return null;
        }

        $min = Carbon::parse(min($timestamps))->subHours(2);
        $max = Carbon::parse(max($timestamps))->addHours(2);

        $events = VisitorEvent::query()
            ->where('visitor_id', $visitorId)
            ->where('created_at', '>=', $min)
            ->where('created_at', '<=', $max)
            ->get();

        return $events->sortBy(fn (VisitorEvent $event) => [
            collect($timestamps)->min(fn ($timestamp) => abs(Carbon::parse($timestamp)->diffInSeconds($event->created_at, false))),
            $event->type === 'whatsapp_click' ? 0 : 1,
        ]
        )->first();
    }

    private function extractMessageTimestamps(string $transcript, ?bool $formatHint = null): array
    {
        $timestamps = [];
        $timezone = 'America/Costa_Rica';
        $pattern = '/^\s*\[?(\d{1,2}[\/.\-]\d{1,2}[\/.\-]\d{2,4}),?\s+(\d{1,2}:\d{2}(?::\d{2})?\s*(?:[ap]\.?\s*m\.?)?)\]?\s*(?:-|–|—|\]|\s)/imu';
        $dayFirst = $formatHint ?? $this->inferTranscriptDateOrder($transcript);

        foreach (preg_split('/\R/u', $transcript) ?: [] as $line) {
            if (!preg_match($pattern, $line, $match)) {
                continue;
            }

            $date = str_replace(['.', '-'], '/', $match[1]);
            $time = preg_replace('/\s+/u', ' ', trim($match[2]));
            $hasMeridiem = (bool) preg_match('/[ap]\.?\s*m\.?$/i', $time);
            $dateFormats = $dayFirst ? ['!d/m/y', '!d/m/Y', '!m/d/y', '!m/d/Y'] : ['!m/d/y', '!m/d/Y', '!d/m/y', '!d/m/Y'];
            $formats = [];
            foreach ($dateFormats as $dateFormat) {
                foreach ($hasMeridiem ? ['g:i A', 'g:i a'] : ['H:i', 'H:i:s'] as $timeFormat) {
                    $formats[] = $dateFormat . ', ' . $timeFormat;
                }
            }

            foreach ($formats as $format) {
                $normalizedTime = preg_replace('/\s*([ap])\.?\s*m\.?$/i', ' $1M', $time);
                try {
                    $parsed = Carbon::createFromFormat($format, $date . ', ' . $normalizedTime, $timezone);
                } catch (\Throwable) {
                    $parsed = false;
                }
                if ($parsed !== false) {
                    $timestamps[] = $parsed->setTimezone(config('app.timezone'))->toIso8601String();
                    break;
                }
            }
        }

        return $timestamps;
    }

    /** true = día/mes, false = mes/día; null = no se puede inferir. */
    private function timestampFormatHint(string $filename): ?bool
    {
        if (preg_match('/\b(?:MM|month)\s*[-_ ]?\s*(?:DD|day)\b/i', $filename)) {
            return false;
        }
        if (preg_match('/\b(?:DD|day)\s*[-_ ]?\s*(?:MM|month)\b/i', $filename)) {
            return true;
        }

        return null;
    }

    private function inferTranscriptDateOrder(string $transcript): bool
    {
        if (preg_match_all('/^\s*\[?(\d{1,2})[\/.\-](\d{1,2})[\/.\-]\d{2,4}/mu', $transcript, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                if ((int) $match[1] > 12) {
                    return true;
                }
                if ((int) $match[2] > 12) {
                    return false;
                }
            }
        }

        // Costa Rica/LatAm usa día/mes cuando ambas partes son ambiguas.
        return true;
    }

    private function extractContactIdentity(string $filename, string $transcript): array
    {
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $name = preg_replace('/^(?:chat\s+de\s+whatsapp\s+con|whatsapp\s+chat\s+(?:with|[-–—])|chat\s+(?:with|[-–—]))\s*/iu', '', $name);
        $name = preg_replace('/\s*\(file attached\).*$/iu', '', $name);
        $name = trim($name);
        $name = preg_replace('/^(?:chat|whatsapp)?\s*[-–—]?\s*\d{1,2}[\/.\-]\d{1,2}[\/.\-]\d{2,4}.*$/iu', '', $name);
        if (preg_match('/^(chat|conversation|whatsapp|export|messages?)$/iu', $name)) {
            $name = '';
        }
        $phone = null;

        if (preg_match('/(?<!\d)(?:\+?506[\s.-]?)?[2-8]\d{3}[\s.-]?\d{4}(?!\d)/', $name, $match)) {
            $phone = trim($match[0]);
            $name = trim(str_replace($match[0], '', $name), " -_()");
        }

        // En chats exportados el contacto también puede aparecer como remitente.
        if (!$name) {
            $senders = [];
            $pattern = '/^\s*\[?\d{1,2}[\/.\-]\d{1,2}[\/.\-]\d{2,4},?\s+\d{1,2}:\d{2}(?::\d{2})?(?:\s*[ap]\.?(?:\s*m\.?)?)?\]?\s*(?:-|–|—)?\s*([^:]{1,100}):/iu';
            foreach (preg_split('/\R/u', $transcript) ?: [] as $line) {
                if (!preg_match($pattern, $line, $match)) {
                    continue;
                }
                $sender = trim($match[1]);
                if (preg_match('/^(you|tú|tu|usted|yo|invicta(?: costa rica)?)$/iu', $sender)) {
                    continue;
                }
                if (!$phone && preg_match('/(?<!\d)(?:\+?506[\s.-]?)?[2-8]\d{3}[\s.-]?\d{4}(?!\d)/', $sender, $phoneMatch)) {
                    $phone = trim($phoneMatch[0]);
                    continue;
                }
                $senders[$sender] = ($senders[$sender] ?? 0) + 1;
            }
            if ($senders) {
                arsort($senders);
                $name = (string) array_key_first($senders);
            }
        }

        return [
            'name' => $name !== '' ? mb_substr($name, 0, 255) : null,
            'phone' => $phone,
        ];
    }
}
