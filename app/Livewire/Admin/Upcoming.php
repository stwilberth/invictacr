<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Services\InvictaWatchScraper;
use App\Services\DeepseekTranslationService;
use Livewire\Component;
use Livewire\WithPagination;

class Upcoming extends Component
{
    use WithPagination;

    private const MAX_LOG_ENTRIES = 150;

    public $search = '';
    public $modelosInput = '';
    public $importLog = [];
    public $importing = false;
    public $totalModelos = 0;
    public $processedModelos = 0;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function activate($productId)
    {
        $product = Product::findOrFail($productId);
        $product->update(['proximo' => false]);
        session()->flash('message', "Producto {$product->modelo} activado. Permanecerá oculto hasta que el sincronizador le asigne precio desde VariedadesCR.");
    }

    public function clearAll()
    {
        if ($this->importing) {
            session()->flash('error', 'Hay una importación en curso. Espere a que termine.');
            return;
        }

        if (!$this->backupDb()) {
            session()->flash('error', 'No se pudo crear el backup de la base de datos. No se eliminó nada.');
            return;
        }

        Product::where('proximo', true)->delete();
        $this->importLog = [];
        session()->forget('upcoming_models');
        session()->flash('message', 'Todos los productos próximos han sido eliminados.');
    }

    public function startImport()
    {
        if ($this->importing) {
            return;
        }

        $this->importLog = [];
        $this->processedModelos = 0;

        $modelos = preg_split('/[\s,;]+/', mb_strtoupper(trim($this->modelosInput)));
        $modelos = array_values(array_unique(array_filter($modelos)));

        if (empty($modelos)) {
            session()->flash('error', 'Ingrese al menos un modelo.');
            return;
        }

        $this->importing = true;
        $this->totalModelos = count($modelos);

        $this->log('info', "Iniciando importación de {$this->totalModelos} modelos...");

        if ($this->backupDb()) {
            $this->log('info', 'Backup de DB completado.');
        } else {
            $this->log('error', 'No se pudo crear el backup de la base de datos. La importación continuará sin backup.');
        }

        session()->put('upcoming_models', $modelos);
        $this->modelosInput = '';

        $this->dispatch('import-started');
    }

    public function processNext()
    {
        $models = session('upcoming_models', []);
        if (empty($models)) {
            $this->log('done', 'Importación completada.');
            $this->importing = false;
            session()->forget('upcoming_models');
            return;
        }

        $modelo = array_shift($models);
        session()->put('upcoming_models', $models);
        $this->processedModelos++;

        try {
            $this->processModelo($modelo);
        } catch (\Throwable $e) {
            $this->log('error', 'Error inesperado: ' . $e->getMessage(), $modelo);
        }

        $this->dispatch('model-processed');
    }

    public function failImport(string $message)
    {
        $this->log('error', $message);
        $this->importing = false;
        session()->forget('upcoming_models');
    }

    private function processModelo(string $modelo): void
    {
        $existing = Product::where('modelo', $modelo)->first();
        if ($existing) {
            $this->log('skipped', "Ya existe (ID: {$existing->id})", $modelo);
            return;
        }

        $this->log('scraping', 'Consultando InvictaWatch...', $modelo);

        try {
            $scraper = app(InvictaWatchScraper::class);
            $data = $scraper->scrape($modelo);
        } catch (\Exception $e) {
            $data = null;
            $this->log('error', 'Error al scrapear: ' . $e->getMessage(), $modelo);
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
            $this->log('created_basic', 'Creado (InvictaWatch no respondió)', $modelo);
            return;
        }

        $this->log('scraped', 'Datos obtenidos: ' . ($data['title'] ?? ''), $modelo);

        $title = trim($data['title'] ?? '');
        if ($title && preg_match('/^\d+\s*-\s*(.+)$/', $title, $m)) {
            $title = trim($m[1]);
        }
        if ($title && !str_contains($title, 'Invicta')) {
            $title = 'Invicta ' . $title;
        }
        if (!$title) {
            $title = "Invicta {$modelo}";
        }

        $descripcion = $data['descripcion'] ?? null;
        if (empty($descripcion)) {
            $translator = app(DeepseekTranslationService::class);
            $descripcion = $translator->translateDescription($data);
            if ($descripcion) {
                $this->log('info', 'Descripción generada con IA', $modelo);
            }
        }

        if ($data['imagen_local']) {
            $this->log('image_ok', 'Imagen descargada: ' . $data['imagen_local'], $modelo);
        } else {
            $this->log('image_fail', 'No se pudo descargar imagen', $modelo);
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
            'size' => $this->sanitizeNumeric($data['size']),
            'caja' => $data['caja'],
            'brazalete' => $data['brazalete'],
            'tipo_movimiento' => $data['tipo_movimiento'],
            'resistencia_agua' => $this->sanitizeNumeric($data['resistencia_agua']),
            'imagen' => $data['imagen_local'],
            'bloqueado' => false,
            'proximo' => true,
            'activo' => true,
            'vistas' => 0,
        ]);

        $this->log('created', 'Producto creado exitosamente', $modelo);
    }

    private function log(string $type, string $message, string $modelo = ''): void
    {
        $this->importLog[] = [
            'type' => $type,
            'modelo' => $modelo,
            'message' => $message,
        ];

        if (count($this->importLog) > self::MAX_LOG_ENTRIES) {
            $this->importLog = array_slice($this->importLog, -self::MAX_LOG_ENTRIES);
        }
    }

    private function backupDb(): bool
    {
        try {
            $db = config('database.connections.mysql');
            $filename = 'invictacr_pre_upcoming_' . date('Ymd_His') . '.sql.gz';
            $dir = storage_path('backups');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $path = $dir . '/' . $filename;

            $cmd = sprintf(
                'mysqldump --host=%s --port=%s --user=%s --password=%s %s 2>/dev/null | gzip > %s',
                escapeshellarg($db['host'] ?? '127.0.0.1'),
                escapeshellarg((string) ($db['port'] ?? '3306')),
                escapeshellarg($db['username']),
                escapeshellarg($db['password'] ?? ''),
                escapeshellarg($db['database']),
                escapeshellarg($path)
            );
            exec($cmd, $output, $exitCode);

            if ($exitCode === 0 && is_file($path) && filesize($path) > 1024) {
                return true;
            }

            @unlink($path);
            return false;
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function sanitizeNumeric(mixed $value): ?string
    {
        if (is_null($value) || trim((string) $value) === '') {
            return null;
        }
        $cleaned = preg_replace('/[^0-9.,]/', '', trim((string) $value));
        $cleaned = str_replace(',', '.', $cleaned);
        $cleaned = preg_replace('/\.(?=.*\.)/', '', $cleaned);
        if (!is_numeric($cleaned)) {
            return null;
        }
        $num = (float) $cleaned;
        return $num == intval($num) ? (string) intval($num) : rtrim(rtrim(sprintf('%.1f', $num), '0'), '.');
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
