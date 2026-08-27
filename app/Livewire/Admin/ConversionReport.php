<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\VisitorEvent;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class ConversionReport extends Component
{
    use WithPagination;

    public $days = 14;
    public $search = "";
    public $sortField = "comprar_clicks";
    public $sortDirection = "desc";
    public $topProducts = [];

    protected $queryString = ["days"];

    public function mount()
    {
        $this->loadTopProducts();
    }

    public function updatedDays()
    {
        $this->resetPage();
        $this->loadTopProducts();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === "asc" ? "desc" : "asc";
        } else {
            $this->sortField = $field;
            $this->sortDirection = "asc";
        }
    }

    public function loadTopProducts()
    {
        $this->topProducts = VisitorEvent::select(
            "p.id",
            "p.modelo",
            DB::raw("SUM(v.type = 'product_view')            AS vistas"),
            DB::raw("SUM(v.type = 'add_to_cart')              AS carritos"),
            DB::raw("SUM(v.type = 'whatsapp_click')           AS whatsapp"),
            DB::raw("SUM(v.type = 'cta_click' AND JSON_EXTRACT(v.meta, '$.cta') = 'comprar-ahora')      AS comprar_clicks"),
            DB::raw("SUM(v.type = 'cta_click' AND JSON_EXTRACT(v.meta, '$.cta') = 'comprar-whatsapp')  AS whatsapp_clicks"),
            DB::raw("SUM(v.type = 'cta_click' AND JSON_EXTRACT(v.meta, '$.cta') = 'ver-disponibilidad') AS disponibilidad_clicks"),
            DB::raw("SUM(v.type = 'cta_click')                 AS cta_total")
        )
            ->from("visitor_events as v")
            ->join("products as p", "p.id", "=", "v.product_id")
            ->where("v.created_at", ">=", now()->subDays((int) $this->days))
            ->groupBy("p.id", "p.modelo")
            ->orderBy($this->sortField, $this->sortDirection)
            ->take(25)
            ->get()
            ->map(function ($row) {
                $row->tasa = $row->vistas > 0 ? round(($row->carritos / $row->vistas) * 100, 1) : 0;
                return $row;
            })
            ->toArray();
    }

    public function render()
    {
        $since = now()->subDays((int) $this->days);

        $productIds = VisitorEvent::whereIn("type", ["product_view", "add_to_cart", "whatsapp_click", "cta_click"])
            ->where("created_at", ">=", $since)
            ->distinct()
            ->pluck("product_id");

        $products = Product::whereIn("id", $productIds)
            ->when($this->search, function ($q) {
                $q->where(function ($w) {
                    $w->where("modelo", "like", "%{$this->search}%")
                        ->orWhere("title", "like", "%{$this->search}%");
                });
            })
            ->orderBy("modelo", "asc")
            ->paginate(20);

        $rows = $products->getCollection()->map(function ($product) use ($since) {
            $counts = VisitorEvent::where("product_id", $product->id)
                ->where("created_at", ">=", $since)
                ->whereIn("type", ["product_view", "add_to_cart", "whatsapp_click", "cta_click"])
                ->get()
                ->reduce(function ($acc, $e) {
                    $acc[$e->type]++;
                    if ($e->type === "cta_click") {
                        $acc["cta_" . ($e->meta["cta"] ?? "otro")]++;
                    }
                    return $acc;
                }, [
                    "product_view" => 0,
                    "add_to_cart" => 0,
                    "whatsapp_click" => 0,
                    "cta_click" => 0,
                    "cta_comprar-ahora" => 0,
                    "cta_comprar-whatsapp" => 0,
                    "cta_ver-disponibilidad" => 0,
                    "cta_otro" => 0,
                ]);

            $counts["tasa"] = $counts["product_view"] > 0
                ? round(($counts["add_to_cart"] / $counts["product_view"]) * 100, 1)
                : 0;

            return (object) array_merge([
                "id" => $product->id,
                "modelo" => $product->modelo,
                "title" => $product->title,
                "imagen" => $product->imagen,
                "precio_venta" => $product->precio_venta,
            ], $counts);
        });

        $products->setCollection(collect($rows));

        $totals = [
            "vistas"       => VisitorEvent::where("type", "product_view")->where("created_at", ">=", $since)->count(),
            "cta_click"    => VisitorEvent::where("type", "cta_click")->where("created_at", ">=", $since)->count(),
            "add_to_cart"  => VisitorEvent::where("type", "add_to_cart")->where("created_at", ">=", $since)->count(),
            "whatsapp"     => VisitorEvent::where("type", "whatsapp_click")->where("created_at", ">=", $since)->count(),
            "carrito_tasa" => 0,
        ];
        $totals["carrito_tasa"] = $totals["vistas"] > 0 ? round(($totals["add_to_cart"] / $totals["vistas"]) * 100, 1) : 0;

        $byCta = VisitorEvent::where("type", "cta_click")
            ->where("created_at", ">=", $since)
            ->get(["meta"])
            ->reduce(function ($acc, $e) {
                $acc[$e->meta["cta"] ?? "otro"]++;
                return $acc;
            }, [
                "comprar-ahora" => 0,
                "comprar-whatsapp" => 0,
                "ver-disponibilidad" => 0,
                "otro" => 0,
            ]);

        return view("livewire.admin.conversion-report", [
            "products" => $products,
            "rows" => $rows,
            "totals" => $totals,
            "byCta" => $byCta,
        ])->layout("components.admin-layout", ["title" => "Conversión por modelo"]);
    }
}
