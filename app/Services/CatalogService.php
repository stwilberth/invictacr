<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Servicio del catálogo de /relojes.
 *
 * La lista base de productos activos (solo las columnas mínimas que usa la
 * tarjeta) se cachea en Redis y se filtra en memoria por request. Esto evita
 * consultas paginadas, quita el scroll infinito y deja que cualquier combinación
 * de filtros cargue todo el resultado de una vez. Se invalida con
 * Product::forgetAllCache() (version counter) o naturalmente por TTL.
 */
class CatalogService
{
    private const MINIMAL_COLUMNS = [
        'id',
        'modelo',
        'title',
        'slug',
        'imagen',
        'precio_venta',
        'descuento',
        'proximo',
        'stock',
        'disponibilidad',
        'tipo_movimiento',
        'video_uid',
        'genero',
        'color',
        'brazalete',
        'coleccion',
        'caja',
        'resistencia_agua',
        'size',
        'descripcion',
        'vistas',
        'created_at',
    ];

    private const VERSION_KEY = 'product:catalog:version';

    public function version(): int
    {
        return (int) cache()->get(self::VERSION_KEY, 0);
    }

    public function invalidate(): void
    {
        cache()->increment(self::VERSION_KEY);
    }

    /**
     * Lista base de productos activos, cacheada en Redis. Solo columnas mínimas.
     * Se guarda como arrays planos (no modelos) para evitar problemas de
     * serialización de objetos al leer desde Redis; al leer se hidratan.
     */
    public function baseProducts(): Collection
    {
        $key = "product:catalog:v{$this->version()}";

        $rows = cache()->remember($key, now()->addDay(), function () {
            return Product::where('activo', true)
                ->select(self::MINIMAL_COLUMNS)
                ->get()
                ->toArray();
        });

        return Product::hydrate($rows);
    }

    /**
     * Normaliza los filtros del request a un array canónico (siempre con las
     * mismas claves y orden) para poder usarlo como parte de la clave de caché.
     */
    public function extractFilters(Request $request): array
    {
        return [
            'q' => trim((string) $request->input('q', '')),
            'gender' => trim((string) $request->input('gender', '')),
            'color' => trim((string) $request->input('color', '')),
            'brazalete' => trim((string) $request->input('brazalete', '')),
            'coleccion' => trim((string) $request->input('coleccion', '')),
            'tipo_movimiento' => trim((string) $request->input('tipo_movimiento', '')),
            'caja' => trim((string) $request->input('caja', '')),
            'resistencia_agua' => trim((string) $request->input('resistencia_agua', '')),
            'size' => trim((string) $request->input('size', '')),
            'precio_min' => trim((string) $request->input('precio_min', '')),
            'precio_max' => trim((string) $request->input('precio_max', '')),
            'sort' => trim((string) $request->input('sort', '')),
            'proximo' => $request->input('proximo', '') === '0' ? '0' : '',
        ];
    }

    public function filteredFromRequest(Request $request): Collection
    {
        return $this->filtered($this->extractFilters($request));
    }

    /**
     * Adjunta la relación "images" (galería extra del producto) a cada modelo
     * de la colección con una sola consulta. Evita N+1 al renderizar las
     * tarjetas que ahora muestran slider de fotos.
     */
    public function attachImages(Collection $products): Collection
    {
        $ids = $products->pluck('id')->filter()->unique()->values();
        if ($ids->isEmpty()) {
            return $products;
        }

        $images = ProductImage::whereIn('product_id', $ids)
            ->orderBy('product_id')
            ->orderBy('order')
            ->get()
            ->groupBy('product_id');

        return $products->map(function (Product $product) use ($images) {
            $product->setRelation('images', $images->get($product->id) ?? collect());

            return $product;
        })->values();
    }

    /**
     * Aplica los filtros en memoria sobre la lista base. Devuelve todos los
     * resultados de una vez (sin paginación).
     */
    public function filtered(array $filters): Collection
    {
        $products = $this->baseProducts();

        // La búsqueda de texto no filtra por stock/proximo (igual que el SQL original);
        // el gating de disponibilidad solo aplica al catálogo sin consulta.
        $isSearch = (string) ($filters['q'] ?? '') !== '';
        if (! $isSearch) {
            $products = $this->applyAvailability($products, ($filters['proximo'] ?? '') === '0');
        }

        $products = $this->applyTextFilter($products, (string) ($filters['q'] ?? ''));
        $products = $this->applyGender($products, (string) ($filters['gender'] ?? ''));
        $products = $this->applyEqualFilters($products, $filters);
        $products = $this->applyResistencia($products, (string) ($filters['resistencia_agua'] ?? ''));
        $products = $this->applySize($products, (string) ($filters['size'] ?? ''));
        $products = $this->applyPriceRange($products, (string) ($filters['precio_min'] ?? ''), (string) ($filters['precio_max'] ?? ''));
        $products = $this->applyOrdering($products, (string) ($filters['sort'] ?? ''));

        return $products->values();
    }

    private function applyAvailability(Collection $products, bool $hideProximo): Collection
    {
        return $products->filter(function (Product $p) use ($hideProximo) {
            $inStock = (float) $p->precio_venta > 0 && (int) $p->stock > 0;
            $isProximo = (bool) $p->proximo;

            if ($hideProximo) {
                return $inStock && ! $isProximo;
            }

            return $inStock || $isProximo;
        });
    }

    private function applyTextFilter(Collection $products, string $q): Collection
    {
        $text = trim($q);
        if ($text === '') {
            return $products;
        }

        $tokens = $this->tokenize($text);
        if (empty($tokens)) {
            return $products;
        }

        return $products->filter(function (Product $p) use ($tokens) {
            $fields = [
                (string) ($p->modelo ?? ''),
                (string) ($p->title ?? ''),
                (string) ($p->coleccion ?? ''),
                (string) ($p->color ?? ''),
                (string) ($p->genero ?? ''),
                (string) ($p->brazalete ?? ''),
                (string) ($p->tipo_movimiento ?? ''),
                (string) ($p->descripcion ?? ''),
            ];
            $haystack = mb_strtolower(implode(' ', $fields), 'UTF-8');

            foreach ($tokens as $token) {
                if (! str_contains($haystack, $token)) {
                    return false;
                }
            }

            return true;
        });
    }

    private function applyGender(Collection $products, string $value): Collection
    {
        $value = trim(mb_strtolower($value, 'UTF-8'));
        if ($value === '') {
            return $products;
        }

        // Unisex aplica a Hombre y Mujer (misma convención que Product::scopeRelatedTo)
        $allowed = match ($value) {
            'hombre' => ['hombre', 'unisex'],
            'mujer' => ['mujer', 'unisex'],
            default => [$value],
        };

        return $products->filter(function (Product $p) use ($allowed) {
            $raw = mb_strtolower((string) $p->genero, 'UTF-8');

            return in_array($raw, $allowed, true);
        });
    }

    private function applyEqualFilters(Collection $products, array $filters): Collection
    {
        $map = [
            'color' => 'color',
            'brazalete' => 'brazalete',
            'coleccion' => 'coleccion',
            'tipo_movimiento' => 'tipo_movimiento',
            'caja' => 'caja',
        ];

        foreach ($map as $key => $column) {
            $value = trim((string) ($filters[$key] ?? ''));
            if ($value === '') {
                continue;
            }

            $needle = mb_strtolower($value, 'UTF-8');
            $products = $products->filter(function (Product $p) use ($column, $needle) {
                $raw = $p->{$column};

                return $raw !== null && $raw !== '' && mb_strtolower((string) $raw, 'UTF-8') === $needle;
            });
        }

        return $products;
    }

    private function applyResistencia(Collection $products, string $value): Collection
    {
        $value = trim($value);
        if ($value === '') {
            return $products;
        }

        $target = (int) $value;

        return $products->filter(function (Product $p) use ($target) {
            $raw = (string) ($p->resistencia_agua ?? '');

            return (int) preg_replace('/[^0-9]/', '', $raw) === $target;
        });
    }

    private function applySize(Collection $products, string $value): Collection
    {
        $value = trim($value);
        if ($value === '') {
            return $products;
        }

        return $products->filter(function (Product $p) use ($value) {
            $size = (float) preg_replace('/[^0-9.]+/', '', (string) ($p->size ?? ''));
            if ($size <= 0) {
                return false;
            }

            if (str_ends_with($value, '+')) {
                return $size >= (float) substr($value, 0, -1);
            }

            if (str_contains($value, '-')) {
                [$min, $max] = array_map('floatval', explode('-', $value, 2));

                return $size >= $min && $size <= $max;
            }

            return $size === (float) $value;
        });
    }

    private function applyPriceRange(Collection $products, string $min, string $max): Collection
    {
        $minVal = trim($min) === '' ? null : (float) $min;
        $maxVal = trim($max) === '' ? null : (float) $max;

        // El usuario filtra por precio final (con IVA); la BD guarda netos.
        // Conversión inversa aproximada (el redondeo a 500 introduce ±500 de tolerancia).
        $ivaFactor = 1 + ((float) config('pricing.iva_percent', 13) / 100);
        $netMin = $minVal !== null ? max(0, ($minVal - 500) / $ivaFactor) : null;
        $netMax = $maxVal !== null ? $maxVal / $ivaFactor : null;

        return $products->filter(function (Product $p) use ($netMin, $netMax) {
            $price = (float) $p->precio_venta;

            if ($netMin !== null && $price < $netMin) {
                return false;
            }
            if ($netMax !== null && $price > $netMax) {
                return false;
            }

            return true;
        });
    }

    private function applyOrdering(Collection $products, string $sort): Collection
    {
        // Orden "Destacados": vitrina merchandeada (ventas + intención +
        // interés + novedad ponderada). Es el default por marketing: los
        // best-sellers que ya convierten van arriba, no las novedades sin
        // validar ni el precio más bajo (que ancla barato).
        if ($sort === '' || $sort === 'featured') {
            return $this->applyFeaturedOrdering($products);
        }

        [$field, $dir] = match ($sort) {
            'price_asc' => ['precio_venta', 'asc'],
            'price_desc' => ['precio_venta', 'desc'],
            'name_asc' => ['title', 'asc'],
            'name_desc' => ['title', 'desc'],
            'newest' => ['created_at', 'desc'],
            'most_viewed' => ['vistas', 'desc'],
            default => ['created_at', 'desc'],
        };

        $callback = function (Product $p) use ($field) {
            if ($field === 'precio_venta') {
                return (float) $p->precio_venta;
            }
            if ($field === 'vistas') {
                return (int) $p->vistas;
            }
            if ($field === 'title') {
                return mb_strtolower((string) $p->title, 'UTF-8');
            }

            return (string) ($p->{$field} ?? '');
        };

        $sorted = $products->sortBy($callback, SORT_REGULAR, $dir === 'desc');

        // Los "próximos" siempre van al final, después del resto del catálogo.
        $available = $sorted->filter(fn (Product $p) => ! (bool) $p->proximo)->values();
        $upcoming = $sorted->filter(fn (Product $p) => (bool) $p->proximo)->values();

        return $available->concat($upcoming);
    }

    /**
     * Orden "Destacados" (default de /relojes por marketing).
     *
     * Score determinista (sin azar, estable para SEO) que combina:
     *  - Ventas reales (invoice_items, excluye facturas anuladas): peso mayor.
     *  - Intención reciente 90d (visitor_events whatsapp_click/add_to_cart).
     *  - Interés acumulado (vistas, en log para no dominar).
     *  - Merchandising: con descuento, con video, novedad ponderada.
     *  - Penaliza agotados/sin precio para que no ocupen la vitrina.
     *
     * Los "próximos" siempre van al final (igual que el resto de órdenes).
     */
    private function applyFeaturedOrdering(Collection $products): Collection
    {
        $signals = $this->featuredSignals();

        $scored = $products->map(function (Product $p) use ($signals) {
            $id = (int) $p->id;

            $sold = (int) ($signals['sold'][$id] ?? 0);
            $whatsapp = (int) ($signals['whatsapp'][$id] ?? 0);
            $cart = (int) ($signals['cart'][$id] ?? 0);
            $views = (int) ($p->vistas ?? 0);

            $score = min($sold, 20) * 10
                + min($whatsapp, 30) * 5
                + min($cart, 30) * 3
                + log10($views + 1) * 20;

            if ((int) ($p->descuento ?? 0) > 0) {
                $score += 15;
            }
            if (! empty($p->video_uid)) {
                $score += 10;
            }

            // Novedad ponderada: inyecta 2-3 nuevos al top sin dominarlo.
            try {
                $created = $p->created_at ? \Carbon\Carbon::parse($p->created_at) : null;
                if ($created) {
                    $days = $created->diffInDays(now());
                    if ($days <= 30) {
                        $score += 25;
                    } elseif ($days <= 60) {
                        $score += 12;
                    }
                }
            } catch (\Throwable $e) {
                //
            }

            // Agotados/sin precio no deben ocupar la vitrina (en búsquedas
            // pueden aparecer, pero al fondo del grupo disponible).
            if ((int) ($p->stock ?? 0) <= 0 || (float) ($p->precio_venta ?? 0) <= 0) {
                $score -= 500;
            }

            return ['product' => $p, 'score' => $score];
        });

        // Score desc, desempate: más nuevos primero, luego id (estable/SEO).
        $sorted = $scored->sort(function ($a, $b) {
            if ($a['score'] !== $b['score']) {
                return $b['score'] <=> $a['score'];
            }
            $ca = (string) ($a['product']->created_at ?? '');
            $cb = (string) ($b['product']->created_at ?? '');
            if ($ca !== $cb) {
                return $cb <=> $ca;
            }

            return (int) $b['product']->id <=> (int) $a['product']->id;
        })->map(fn ($row) => $row['product'])->values();

        $available = $sorted->filter(fn (Product $p) => ! (bool) $p->proximo)->values();
        $upcoming = $sorted->filter(fn (Product $p) => (bool) $p->proximo)->values();

        return $available->concat($upcoming);
    }

    /**
     * Señales agregadas para Destacados, cacheadas 6h. Falla en vacío
     * (el orden degrada a vistas+novedad) para nunca romper el catálogo.
     *
     * @return array{sold: array<int,int>, whatsapp: array<int,int>, cart: array<int,int>}
     */
    private function featuredSignals(): array
    {
        try {
            return cache()->remember('product:featured:signals:v1', now()->addHours(6), function () {
                $sold = [];
                try {
                    $rows = \Illuminate\Support\Facades\DB::table('invoice_items')
                        ->join('invoices', 'invoices.id', '=', 'invoice_items.invoice_id')
                        ->where('invoices.status', '!=', 'cancelled')
                        ->whereNotNull('invoice_items.product_id')
                        ->groupBy('invoice_items.product_id')
                        ->selectRaw('invoice_items.product_id as product_id, SUM(invoice_items.quantity) as qty')
                        ->get();
                    foreach ($rows as $row) {
                        $sold[(int) $row->product_id] = (int) $row->qty;
                    }
                } catch (\Throwable $e) {
                    //
                }

                $whatsapp = [];
                $cart = [];
                try {
                    $since = now()->subDays(90);
                    $rows = \Illuminate\Support\Facades\DB::table('visitor_events')
                        ->whereIn('type', ['whatsapp_click', 'add_to_cart'])
                        ->where('created_at', '>=', $since)
                        ->whereNotNull('product_id')
                        ->groupBy('product_id', 'type')
                        ->selectRaw('product_id, type, COUNT(*) as c')
                        ->get();
                    foreach ($rows as $row) {
                        $pid = (int) $row->product_id;
                        if ($row->type === 'whatsapp_click') {
                            $whatsapp[$pid] = (int) $row->c;
                        } elseif ($row->type === 'add_to_cart') {
                            $cart[$pid] = (int) $row->c;
                        }
                    }
                } catch (\Throwable $e) {
                    //
                }

                return ['sold' => $sold, 'whatsapp' => $whatsapp, 'cart' => $cart];
            });
        } catch (\Throwable $e) {
            return ['sold' => [], 'whatsapp' => [], 'cart' => []];
        }
    }

    private function tokenize(string $text): array
    {
        $raw = preg_split('/\s+/', trim($text));
        if ($raw === false) {
            return [];
        }

        $tokens = [];
        foreach ($raw as $tok) {
            $tok = trim($tok, ".,()[]{}");
            if ($tok === '') {
                continue;
            }
            if (preg_match('/^(.+?)mm$/i', $tok, $m)) {
                $tok = $m[1];
            }
            if (mb_strlen($tok) >= 2) {
                $tokens[] = mb_strtolower($tok, 'UTF-8');
            }
        }

        return array_values(array_unique($tokens));
    }
}