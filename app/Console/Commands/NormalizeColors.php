<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class NormalizeColors extends Command
{
    protected $signature = 'invicta:normalize-colors {--dry-run : Solo mostrar los cambios, no escribir}';

    protected $description = 'Normaliza los colores de products contra la lista canonical de config/colors.php';

    public function handle(): int
    {
        $dry = (bool) $this->option('dry-run');

        $canonical = config('colors');
        if (!is_array($canonical) || empty($canonical)) {
            $this->error('config/colors.php está vacío o no existe.');
            return 1;
        }

        // Mapa: lowercase(canonical) => canonical
        $map = [];
        foreach ($canonical as $name) {
            $map[mb_strtolower(trim($name))] = $name;
        }

        $distinct = Product::whereNotNull('color')
            ->where('color', '!=', '')
            ->select('color')
            ->distinct()
            ->pluck('color')
            ->sortBy(fn ($v) => mb_strtolower($v))
            ->values();

        if ($distinct->isEmpty()) {
            $this->info('No hay productos con color asignado.');
            return 0;
        }

        $changes = [];
        $orphans = [];

        foreach ($distinct as $raw) {
            $clean = trim((string) $raw);
            $clean = preg_replace('/\s+/u', ' ', $clean);
            $key = mb_strtolower($clean);

            if (isset($map[$key])) {
                $target = $map[$key];
                if ($target !== $raw) {
                    $changes[] = ['from' => $raw, 'to' => $target];
                }
            } else {
                $orphans[] = $raw;
            }
        }

        // NULL y vacíos -> Otros
        $nullCount = Product::whereNull('color')->count();
        $emptyCount = Product::where('color', '')->count();
        $nullishTotal = $nullCount + $emptyCount;

        if ($changes) {
            $this->table(['Actual', '=> Canonical'], $changes);
            $this->line(sprintf('  %d valor(es) a normalizar.', count($changes)));
        } else {
            $this->info('No hay valores que normalizar (todos ya son canónicos).');
        }

        if ($nullishTotal > 0) {
            $this->newLine();
            $this->line(sprintf('NULL/vacíos -> Otros: %d (NULL: %d, vacíos: %d)', $nullishTotal, $nullCount, $emptyCount));
        }

        if ($orphans) {
            $this->newLine();
            $this->warn('Valores SIN equivalente en la lista canonical (revisar manualmente):');
            foreach ($orphans as $o) {
                $this->line('  • ' . $o);
            }
            $this->line('  Agrega el nombre correcto a config/colors.php o corrige el producto.');
        }

        if ($dry) {
            $this->newLine();
            $this->comment('Modo --dry-run: no se modificó la base de datos.');
            return 0;
        }

        if (empty($changes) && $nullishTotal === 0) {
            return 0;
        }

        if (!$this->confirm('¿Aplicar la normalización en la base de datos de producción?', true)) {
            $this->info('Cancelado. No se modificó nada.');
            return 0;
        }

        DB::transaction(function () use ($changes, $nullishTotal) {
            foreach ($changes as $c) {
                $updated = Product::where('color', $c['from'])->update(['color' => $c['to']]);
                $this->line(sprintf('  %s => %s (%d filas)', $c['from'], $c['to'], $updated));
            }
            if ($nullishTotal > 0) {
                $updated = Product::whereNull('color')->orWhere('color', '')->update(['color' => 'Otros']);
                $this->line(sprintf('  NULL/vacíos => Otros (%d filas)', $updated));
            }
        });

        $this->newLine();
        $this->info('Normalización completada.');
        return 0;
    }
}
