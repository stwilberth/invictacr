<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Services\InvictaWatchScraper;
use App\Services\DeepseekTranslationService;
use Livewire\Component;

class Upcoming extends Component
{
    public $search = '';
    public $modelosInput = '';
    public $importLog = [];
    public $importing = false;
    public $totalModelos = 0;
    public $processedModelos = 0;

    public function activate($productId)
    {
        $product = Product::findOrFail($productId);
        $product->update([
            'proximo' => false,
            'precio_venta' => $product->precio_original ?? 1,
            'stock' => max(1, $product->stock),
        ]);
        session()->flash('message', "Producto {$product->modelo} activado.");
    }

    public function clearAll()
    {
        $this->backupDb();
        Product::where('proximo', true)->delete();
        $this->importLog = [];
        session()->flash('message', 'Todos los productos próximos han sido eliminados.');
    }

    public function startImport()
    {
        $this->importing = true;
        $this->importLog = [];
        $this->processedModelos = 0;

        $modelos = preg_split('/[\s,;]+/', trim($this->modelosInput));
        $modelos = array_filter(array_unique($modelos));

        if (empty($modelos)) {
            session()->flash('error', 'Ingrese al menos un modelo.');
            $this->importing = false;
            return;
        }

        $this->backupDb();
        $this->totalModelos = count($modelos);

        $this->importLog[] = [
            'type' => 'info',
            'modelo' => '',
            'message' => "Iniciando importación de {$this->totalModelos} modelos...",
        ];

        $this->importLog[] = [
            'type' => 'info',
            'modelo' => '',
            'message' => 'Backup de DB completado.',
        ];

        session()->put('upcoming_models', array_values(array_map('trim', $modelos)));
        $this->modelosInput = '';

        $this->dispatch('import-started');
    }

    public function processNext()
    {
        $models = session('upcoming_models', []);
        if (empty($models)) {
            $this->importLog[] = [
                'type' => 'done',
                'modelo' => '',
                'message' => 'Importación completada.',
            ];
            $this->importing = false;
            return;
        }

        $modelo = strtoupper(array_shift($models));
        session()->put('upcoming_models', $models);
        $this->processedModelos++;

        $existing = Product::where('modelo', $modelo)->first();
        if ($existing) {
            $this->importLog[] = [
                'type' => 'skipped',
                'modelo' => $modelo,
                'message' => "Ya existe (ID: {$existing->id})",
            ];
            $this->dispatch('model-processed');
            return;
        }

        $this->importLog[] = [
            'type' => 'scraping',
            'modelo' => $modelo,
            'message' => 'Consultando InvictaWatch...',
        ];

        try {
            $scraper = app(InvictaWatchScraper::class);
            $data = $scraper->scrape($modelo);
        } catch (\Exception $e) {
            $data = null;
            $this->importLog[] = [
                'type' => 'error',
                'modelo' => $modelo,
                'message' => "Error al scrapear: " . $e->getMessage(),
            ];
        }

        if (!$data) {
            Product::create([
                'modelo' => $modelo,
                'title' => "Invicta {$modelo}",
                'slug' => 'invicta-' . strtolower($modelo),
                'precio_venta' => 0,
                'precio_original' => null,
                'stock' => 0,
                'bloqueado' => false,
                'proximo' => true,
                'activo' => true,
                'vistas' => 0,
            ]);
            $this->importLog[] = [
                'type' => 'created_basic',
                'modelo' => $modelo,
                'message' => 'Creado (InvictaWatch no respondió)',
            ];
            $this->dispatch('model-processed');
            return;
        }

        $this->importLog[] = [
            'type' => 'scraped',
            'modelo' => $modelo,
            'message' => 'Datos obtenidos: ' . ($data['title'] ?? ''),
        ];

        $title = $data['title'];
        if (preg_match('/^\d+\s*-\s*(.+)$/', $title, $m)) {
            $title = trim($m[1]);
        }
        if (!str_contains($title, 'Invicta')) {
            $title = 'Invicta ' . $title;
        }

        $descripcion = $data['descripcion'] ?? null;
        if (empty($descripcion)) {
            $translator = app(DeepseekTranslationService::class);
            $descripcion = $translator->translateDescription($data);
            if ($descripcion) {
                $this->importLog[] = [
                    'type' => 'info',
                    'modelo' => $modelo,
                    'message' => 'Descripción generada con IA',
                ];
            }
        }

        if ($data['imagen_local']) {
            $this->importLog[] = [
                'type' => 'image_ok',
                'modelo' => $modelo,
                'message' => 'Imagen descargada: ' . $data['imagen_local'],
            ];
        } else {
            $this->importLog[] = [
                'type' => 'image_fail',
                'modelo' => $modelo,
                'message' => 'No se pudo descargar imagen',
            ];
        }

        Product::create([
            'modelo' => $modelo,
            'title' => $title,
            'slug' => 'invicta-' . strtolower($modelo),
            'descripcion' => $descripcion,
            'precio_venta' => 0,
            'precio_original' => $data['msrp'],
            'stock' => 0,
            'coleccion' => $data['coleccion'],
            'genero' => $data['genero'],
            'size' => $data['size'],
            'caja' => $data['caja'],
            'brazalete' => $data['brazalete'],
            'tipo_movimiento' => $data['tipo_movimiento'],
            'resistencia_agua' => $data['resistencia_agua'],
            'imagen' => $data['imagen_local'],
            'bloqueado' => false,
            'proximo' => true,
            'activo' => true,
            'vistas' => 0,
        ]);

        $this->importLog[] = [
            'type' => 'created',
            'modelo' => $modelo,
            'message' => 'Producto creado exitosamente',
        ];

        $this->dispatch('model-processed');
    }

    private function backupDb(): void
    {
        $db = config('database.connections.mysql');
        $filename = 'invictacr_pre_upcoming_' . date('Ymd_His') . '.sql.gz';
        $path = storage_path('app/private/backups/' . $filename);
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        $cmd = sprintf(
            'mysqldump --host=%s --user=%s --password=%s %s | gzip > %s',
            escapeshellarg($db['host']),
            escapeshellarg($db['username']),
            escapeshellarg($db['password']),
            escapeshellarg($db['database']),
            escapeshellarg($path)
        );
        exec($cmd, $output, $exitCode);
    }

    public function render()
    {
        $query = Product::where('proximo', true);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('modelo', 'like', "%{$this->search}%");
            });
        }

        $products = $query->latest()->paginate(20);

        return view('livewire.admin.upcoming', compact('products'))
            ->layout('components.admin-layout');
    }
}
