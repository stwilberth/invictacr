<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\MarketingTask;
use App\Models\DownloadHistory;
use Livewire\Component;
use Illuminate\Support\Facades\Http;

class Campaigns extends Component
{
    public $activeTab = 'create';
    public $selectedProductId;
    public $product = null;
    public $productSearch = '';
    public $templateType = 'instagram';
    public $generatedContent = null;

    public $aiTone = 'casual';
    public $aiGenerated = null;
    public $aiLoading = false;

    public $utmUrl = '';
    public $utmSource = 'instagram';
    public $utmMedium = 'post';
    public $utmCampaign = '';
    public $utmTerm = '';
    public $utmContent = '';
    public $generatedUtm = '';

    public $savedAds;
    public $productFilter = 'all';
    public $downloads;

    public function saveDownload()
    {
        if (!$this->product) return;

        $existing = DownloadHistory::where('product_id', $this->product->id)->first();
        if ($existing) return;

        $text = $this->generatedContent
            ? ($this->generatedContent['headline'] ?? '') . "\n\n" . ($this->generatedContent['body'] ?? '')
            : '';

        DownloadHistory::create([
            'product_id' => $this->product->id,
            'model_code' => $this->product->modelo,
            'product_image' => $this->product->imagen,
            'text_content' => $text,
        ]);

        $this->loadDownloads();
        $this->dispatch('trigger-png-download');
        session()->flash('message', 'Descarga registrada.');
    }

    public function resetDownloads()
    {
        DownloadHistory::truncate();
        $this->loadDownloads();
        session()->flash('message', 'Historial de descargas limpiado.');
    }

    public function setProductFilter($filter)
    {
        $this->productFilter = $filter;
    }

    private function loadDownloads()
    {
        $this->downloads = DownloadHistory::with('product')
            ->latest()
            ->get();
    }

    public function getAiToneOptionsProperty(): array
    {
        return [
            'casual' => ['fa-face-smile', 'Casual'],
            'profesional' => ['fa-briefcase', 'Pro'],
            'urgente' => ['fa-bolt', 'Urgente'],
            'lujoso' => ['fa-crown', 'Lujo'],
        ];
    }

    public function mount()
    {
        $this->loadDownloads();
        $first = Product::where('activo', true)->where('precio_venta', '>', 0)->orderBy('modelo')->first();
        if ($first) {
            $this->selectedProductId = $first->id;
            $this->product = $first;
            $this->loadProductData($first);
        }
    }

    public function updatedSelectedProductId($value)
    {
        $this->product = $value ? Product::find($value) : null;
        $this->generatedContent = null;

        if ($this->product) {
            $this->loadProductData($this->product);
        }
    }

    private function loadProductData(Product $product): void
    {
        $this->dispatch('populate-image-fields', payload: $this->buildImageTemplateData($product));
        $this->generateAd(silent: true);
    }

    /**
     * Construye los datos de la plantilla de imagen a partir de un producto.
     */
    private function buildImageTemplateData(?Product $product): array
    {
        if (!$product) {
            return [
                'title' => 'INVICTA PRO DIVER',
                'modelCode' => '(49858)',
                'price' => '₡83 000',
                'specs' => "Acero inoxidable\n43 mm\nMov. Cuarzo\n100 m",
                'image' => null,
            ];
        }

        $specs = array_filter([
            $product->size ? $product->size . ' mm' : null,
            $product->resistencia_agua ? $product->resistencia_agua . ' m' : null,
            $product->tipo_movimiento ? ucfirst($product->tipo_movimiento) : null,
            $product->brazalete ?? null,
        ]);

        $title = 'INVICTA ' . strtoupper($product->coleccion ?? $product->modelo ?? '');

        return [
            'title' => $title,
            'modelCode' => $product->codigo_comercial ?? $product->modelo,
            'price' => '₡' . number_format($product->price_after_discount, 0),
            'specs' => $specs ? implode("\n", $specs) : "Acero inoxidable\n43 mm\nMov. Cuarzo\n100 m",
            'image' => $product->imagen ?? null,
        ];
    }

    /**
     * Datos por defecto de la plantilla de imagen para el render inicial
     * (basado en el producto actualmente seleccionado, si lo hay).
     */
    public function getImageTemplateDataProperty(): array
    {
        return $this->buildImageTemplateData($this->product);
    }

    public function generateAd(bool $silent = false)
    {
        $product = Product::find($this->selectedProductId);
        if (!$product) {
            session()->flash('error', 'Selecciona un producto.');
            return;
        }

        $price = $product->price_after_discount;
        $formattedPrice = '₡' . number_format($price, 0);
        $modelo = $product->modelo;
        $coleccion = $product->coleccion ? strtoupper($product->coleccion) : 'INVICTA';
        $size = $product->size;
        $mov = $product->tipo_movimiento;

        // Texto único para todas las redes
        $headline = "{$coleccion} {$modelo} – El estilo que merecés";
        $body = "✨ Conocé el {$modelo} de Invicta.\n\n"
              . "✅ Diseño {$size}mm\n"
              . "✅ Movimiento {$mov}\n"
              . "✅ Resistente al agua\n\n"
              . "💰 {$formattedPrice}\n"
              . "🚚 Envío gratis en GAM\n\n"
              . "📲 ¡Escríbenos al WhatsApp!";
        $cta = '¡Compra ahora!';

        $this->generatedContent = [
            'template' => $this->templateType,
            'headline' => $headline,
            'body' => $body,
            'cta' => $cta,
            'model' => $product->modelo,
            'price' => $price,
            'formatted_price' => $formattedPrice,
            'image' => $product->imagen,
            'product' => $product,
        ];

        if (!$silent) {
            session()->flash('message', 'Anuncio generado exitosamente.');
        }
    }

    public function generateWithAI()
    {
        $this->aiLoading = true;
        $this->aiGenerated = null;

        $product = Product::find($this->selectedProductId);
        if (!$product) {
            session()->flash('error', 'Selecciona un producto.');
            $this->aiLoading = false;
            return;
        }

        $toneLabels = [
            'profesional' => 'profesional y elegante, tono aspiracional',
            'casual' => 'casual y amigable, como un mensaje a un amigo',
            'urgente' => 'urgente con llamado a la acción, edición limitada',
            'lujoso' => 'lujoso y exclusivo, para conocedores',
        ];

        $prompt = "Genera 3 variantes de anuncio en español para un reloj Invicta.
Modelo: {$product->modelo}
Colección: {$product->coleccion}
Características: {$product->size}mm, movimiento {$product->tipo_movimiento}, resistencia al agua {$product->resistencia_agua}, color {$product->color}
Precio: ₡" . number_format($product->price_after_discount, 0) . "
Tono: {$toneLabels[$this->aiTone]}

IMPORTANTE: NO uses markdown ni asteriscos. Usá formato simple.
Para cada variante escribí:
Título: (máx 10 palabras)
Cuerpo: (máx 40 palabras)  
Hashtags: (3 hashtags separados por espacio)

Separá cada variante exactamente con: ---";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.deepseek.key'),
                'Content-Type' => 'application/json',
            ])->timeout(30)->post('https://api.deepseek.com/v1/chat/completions', [
                'model' => 'deepseek-chat',
                'messages' => [['role' => 'user', 'content' => $prompt]],
                'max_tokens' => 600,
                'temperature' => 0.8,
            ]);

            $text = $response->json('choices.0.message.content');

            $variants = collect(explode('---', $text))
                ->map(fn($v) => trim(preg_replace('/\*\*(.*?)\*\*/', '$1', $v)))
                ->filter(fn($v) => !preg_match('/^(claro|aquí\s+tienes|por\s+supuesto|te\s+presento)/i', $v))
                ->values()
                ->toArray();

            $this->aiGenerated = [
                'variants' => $variants,
                'model' => $product->modelo,
                'image' => $product->imagen,
            ];
        } catch (\Exception $e) {
            session()->flash('error', 'Error al generar con IA: ' . $e->getMessage());
        }

        $this->aiLoading = false;
    }

    public function generateUtm()
    {
        $baseUrl = $this->utmUrl ?: url('/');
        $campaign = $this->utmCampaign ?: 'invicta-' . now()->format('M-Y');

        $params = http_build_query(array_filter([
            'utm_source' => $this->utmSource,
            'utm_medium' => $this->utmMedium,
            'utm_campaign' => $campaign,
            'utm_term' => $this->utmTerm,
            'utm_content' => $this->utmContent,
        ]));

        $separator = parse_url($baseUrl, PHP_URL_QUERY) ? '&' : '?';
        $this->generatedUtm = $baseUrl . $separator . $params;
    }

    public function useAiVariant($index)
    {
        if (!$this->aiGenerated || !isset($this->aiGenerated['variants'][$index])) return;

        $text = $this->aiGenerated['variants'][$index];
        // Limpia etiquetas como "Título:", "Cuerpo:", "Hashtags:" del texto plano
        $clean = preg_replace('/^(Título|Cuerpo|Hashtags):\s*/im', '', $text);
        $lines = array_filter(explode("\n", $clean));

        $this->generatedContent = [
            'template' => 'ai',
            'headline' => $lines[0] ?? 'Anuncio IA',
            'body' => implode("\n", array_slice($lines, 1)),
            'cta' => '¡Contáctanos!',
            'model' => $this->aiGenerated['model'],
            'image' => $this->aiGenerated['image'],
            'price' => null,
            'formatted_price' => '',
        ];

        $this->activeTab = 'create';
        session()->flash('message', 'Variante de IA aplicada.');
    }

    public function saveAd()
    {
        if (!$this->generatedContent) return;

        MarketingTask::create([
            'title' => 'Anuncio: ' . ($this->generatedContent['model'] ?? 'Sin modelo'),
            'description' => ($this->generatedContent['headline'] ?? '') . "\n\n" . ($this->generatedContent['body'] ?? ''),
            'type' => 'ad_' . ($this->generatedContent['template'] ?? 'manual'),
            'status' => 'pending',
            'metadata' => [
                'template' => $this->generatedContent['template'] ?? null,
                'model' => $this->generatedContent['model'] ?? null,
                'image' => $this->generatedContent['image'] ?? null,
            ],
        ]);

        session()->flash('message', 'Anuncio guardado en Marketing.');
    }

public function render()
    {
        $query = Product::where('activo', true)->where('precio_venta', '>', 0)
            ->when($this->productSearch, fn($q) => $q->where('modelo', 'like', '%'.$this->productSearch.'%'));

        if ($this->productFilter === 'pending') {
            $downloadedIds = DownloadHistory::pluck('product_id');
            $query->whereNotIn('id', $downloadedIds);
        }

        $products = $query->orderBy('modelo')->get();
        $this->savedAds = MarketingTask::where('type', 'like', 'ad_%')->latest()->take(10)->get();
        $this->loadDownloads();

        return view('livewire.admin.campaigns', compact('products'))
            ->layout('components.admin-layout', ['title' => 'Campañas']);
    }
}
