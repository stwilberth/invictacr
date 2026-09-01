<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\MarketingTask;
use App\Models\DownloadHistory;
use App\Services\CatalogService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class Campaigns extends Component
{
    use WithPagination;
    public $activeTab = 'create';
    public $selectedProductId;
    public $product = null;
    public $productSearch = '';
    public $templateType = 'instagram';
    public $generatedContent = null;

    public $utmUrl = '';
    public $utmSource = 'instagram';
    public $utmMedium = 'post';
    public $utmCampaign = '';
    public $utmTerm = '';
    public $utmContent = '';
    public $generatedUtm = '';

    public $savedAds;
    public $productFilter = 'all';
    public $filterColeccion = '';
    public $filterColor = '';
    public $filterBrazalete = '';
    public $filterGenero = '';
    public $sortField = 'modelo';
    public $sortDirection = 'asc';

    public function saveDownload()
    {
        if (!$this->product) return;

        $text = $this->generatedContent
            ? ($this->generatedContent['headline'] ?? '') . "\n\n" . ($this->generatedContent['body'] ?? '')
            : '';

        $existing = DownloadHistory::where('product_id', $this->product->id)->first();
        if (!$existing) {
            DownloadHistory::create([
                'product_id' => $this->product->id,
                'model_code' => $this->product->modelo,
                'product_image' => $this->product->imagen,
                'text_content' => $text,
            ]);
        }

        $this->dispatch('trigger-png-download');
        session()->flash('message', 'Descarga registrada.');
    }

    public function setProductFilter($filter)
    {
        $this->productFilter = $filter;
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updating($property, $value)
    {
        if (in_array($property, ['productSearch', 'filterColeccion', 'filterColor', 'filterBrazalete', 'filterGenero', 'productFilter', 'sortField', 'sortDirection'])) {
            $this->resetPage();
        }
    }

    public function mount()
    {
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
        $base = (new CatalogService())->baseProducts();

        $available = $base->filter(fn (Product $p) => (float) $p->precio_venta > 0);

        $colecciones = $available->pluck('coleccion')->filter()->unique()->sort()->values();
        $colores = $available->pluck('color')->filter()->unique()->sort()->values();
        $brazaletes = $available->pluck('brazalete')->filter()->unique()->sort()->values();

        $products = $this->paginateProducts($this->filteredProducts());

        $this->savedAds = MarketingTask::where('type', 'like', 'ad_%')->latest()->take(10)->get();

        return view('livewire.admin.campaigns', compact('products', 'colecciones', 'colores', 'brazaletes'))
            ->layout('components.admin-layout', ['title' => 'Campañas']);
    }

    public function exportFiltered()
    {
        $products = $this->filteredProducts();

        if ($products->isEmpty()) {
            session()->flash('error', 'No hay productos para exportar.');
            return;
        }

        $zipPath = storage_path('app/public/productos-filtrados.zip');
        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            session()->flash('error', 'No se pudo crear el ZIP.');
            return;
        }

        $adImage = new \App\Http\Controllers\AdImageController();
        $count = 0;

        foreach ($products as $p) {
            try {
                $png = $adImage->generate($p);
                $zip->addFromString($p->modelo . '.png', $png);
                $count++;
            } catch (\Throwable $e) {
                \Log::warning("Ad image export failed for {$p->modelo}: " . $e->getMessage());
            }
        }

        $zip->close();

        if ($count === 0) {
            session()->flash('error', 'No se pudieron generar las imágenes.');
            return;
        }

        return response()->download($zipPath, 'productos-filtrados.zip')->deleteFileAfterSend(true);
    }

    /**
     * Aplica búsqueda, filtros, filtro de pendientes y orden sobre la lista base
     * de productos (cacheada en Redis vía CatalogService). Trabaja en memoria.
     */
    private function filteredProducts(): Collection
    {
        $products = (new CatalogService())->baseProducts()
            ->filter(fn (Product $p) => (float) $p->precio_venta > 0 && (int) $p->stock > 0)
            ->values();

        if ($this->productSearch !== '') {
            $q = mb_strtolower(trim($this->productSearch), 'UTF-8');
            $products = $products->filter(function (Product $p) use ($q) {
                return str_contains(mb_strtolower((string) $p->modelo, 'UTF-8'), $q)
                    || str_contains(mb_strtolower((string) $p->title, 'UTF-8'), $q);
            })->values();
        }

        if ($this->filterColeccion !== '') {
            $products = $products->filter(fn (Product $p) => $p->coleccion === $this->filterColeccion)->values();
        }
        if ($this->filterColor !== '') {
            $products = $products->filter(fn (Product $p) => $p->color === $this->filterColor)->values();
        }
        if ($this->filterBrazalete !== '') {
            $products = $products->filter(fn (Product $p) => $p->brazalete === $this->filterBrazalete)->values();
        }
        if ($this->filterGenero !== '') {
            $products = $products->filter(fn (Product $p) => $p->genero === $this->filterGenero)->values();
        }

        if ($this->productFilter === 'pending') {
            $downloadedIds = DownloadHistory::pluck('product_id')->flip();
            $products = $products->filter(fn (Product $p) => ! $downloadedIds->has($p->id))->values();
        }

        $field = $this->sortField;
        $dir = $this->sortDirection;

        return $products->sortBy(function (Product $p) use ($field) {
            return match ($field) {
                'precio_venta' => (float) $p->precio_venta,
                'size' => (float) preg_replace('/[^0-9.]+/', '', (string) $p->size),
                'created_at' => (string) $p->created_at,
                default => mb_strtolower((string) $p->modelo, 'UTF-8'),
            };
        }, SORT_REGULAR, $dir === 'desc')->values();
    }

    private function paginateProducts(Collection $products): LengthAwarePaginator
    {
        $perPage = 30;
        $page = LengthAwarePaginator::resolveCurrentPage('page');

        return new LengthAwarePaginator(
            $products->forPage($page, $perPage)->values(),
            $products->count(),
            $perPage,
            $page
        );
    }
}
