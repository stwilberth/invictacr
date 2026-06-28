<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\MarketingTask;
use Livewire\Component;
use Illuminate\Support\Facades\Http;

class Campaigns extends Component
{
    public $activeTab = 'generator';
    public $selectedProductId;
    public $product = null;
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

    public function updatedSelectedProductId($value)
    {
        $this->product = $value ? Product::find($value) : null;
        $this->generatedContent = null;
    }

    public function generateAd()
    {
        $product = Product::find($this->selectedProductId);
        if (!$product) {
            session()->flash('error', 'Selecciona un producto.');
            return;
        }

        $price = $product->price_after_discount;
        $formattedPrice = '₡' . number_format($price, 0);

        $templates = [
            'instagram' => [
                'headline' => "{$product->modelo} – El estilo que merecés",
                'body' => "✨ Conocé el {$product->modelo} de Invicta.\n\n✅ Diseño {$product->size}mm\n✅ Movimiento {$product->tipo_movimiento}\n✅ Resistente al agua\n\n💰 {$formattedPrice}\n🚚 Envío gratis en GAM\n\n📲 ¡Escríbenos al WhatsApp!",
                'cta' => '¡Compra ahora!',
            ],
            'facebook' => [
                'headline' => "🔥 {$product->modelo} – Oferta por tiempo limitado",
                'body' => "No dejes pasar esta oportunidad.\n\n⌚️ Modelo: {$product->modelo}\n📏 Tamaño: {$product->size}mm\n⚙️ Movimiento: {$product->tipo_movimiento}\n💰 Precio: {$formattedPrice}\n\n🔵 Envío gratis en GAM\n📲 Contáctanos hoy",
                'cta' => 'Más información',
            ],
            'whatsapp' => [
                'headline' => "¡Hola! 😊",
                'body' => "Te comparto este increíble reloj Invicta:\n\n⌚️ *{$product->modelo}*\n💰 *{$formattedPrice}*\n📏 {$product->size}mm | ⚙️ {$product->tipo_movimiento}\n💧 Resistencia al agua\n\n¿Te interesa? ¡Escríbenos! 🚀",
                'cta' => '',
            ],
            'story' => [
                'headline' => "{$product->modelo}",
                'body' => "{$formattedPrice}\n🚚 Envío gratis GAM\n📲 Link en bio",
                'cta' => '',
            ],
        ];

        $template = $templates[$this->templateType] ?? $templates['instagram'];

        $this->generatedContent = [
            'template' => $this->templateType,
            'headline' => $template['headline'],
            'body' => $template['body'],
            'cta' => $template['cta'],
            'model' => $product->modelo,
            'price' => $price,
            'formatted_price' => $formattedPrice,
            'image' => $product->imagen,
            'product' => $product,
        ];

        session()->flash('message', 'Anuncio generado exitosamente.');
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

Para cada variante incluye: título llamativo (máx 10 palabras), cuerpo persuasivo (máx 40 palabras), y 3 hashtags relevantes.
Separa cada variante con '---'.";

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
                ->map(fn($v) => trim($v))
                ->filter()
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
        $lines = explode("\n", $text);

        $this->generatedContent = [
            'template' => 'ai',
            'headline' => $lines[0] ?? 'Anuncio IA',
            'body' => $text,
            'cta' => '¡Contáctanos!',
            'model' => $this->aiGenerated['model'],
            'image' => $this->aiGenerated['image'],
            'price' => null,
            'formatted_price' => '',
        ];

        $this->activeTab = 'generator';
        session()->flash('message', 'Variante de IA aplicada al generador.');
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
        $products = Product::where('activo', true)->where('precio_venta', '>', 0)->orderBy('modelo')->get();
        $this->savedAds = MarketingTask::where('type', 'like', 'ad_%')->latest()->take(10)->get();

        return view('livewire.admin.campaigns', compact('products'))
            ->layout('components.admin-layout', ['title' => 'Campañas']);
    }
}
