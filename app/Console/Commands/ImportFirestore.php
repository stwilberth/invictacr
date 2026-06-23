<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Expense;
use App\Models\Subscriber;
use App\Models\Setting;
use App\Models\Combo;
use App\Models\MarketingTask;
use App\Models\SyncLog;
use App\Models\ProductComment;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ImportFirestore extends Command
{
    protected $signature = 'firestore:import {--dir=firebase-export}';
    protected $description = 'Import data from Firestore JSON exports';

    private array $importers = [
        'products' => 'importProducts',
        'productos' => 'importProductsAlias',
        'invoices' => 'importInvoices',
        'clients' => 'importClients',
        'expenses' => 'importExpenses',
        'suscriptores' => 'importSubscribers',
        'settings' => 'importSettings',
        'combos' => 'importCombos',
        'marketing_tasks' => 'importMarketingTasks',
        'product_comments' => 'importProductComments',
        'sync_logs' => 'importSyncLogs',
    ];

    public function handle()
    {
        $dir = $this->option('dir');
        $path = storage_path("app/private/{$dir}");

        if (!is_dir($path)) {
            $this->error("Directory storage/app/{$path} not found. Run the export script first.");
            return 1;
        }

        $files = glob("{$path}/*.json");
        $this->info("Found " . count($files) . " files to import.");

        foreach ($files as $file) {
            $filename = pathinfo($file, PATHINFO_FILENAME);
            $method = $this->importers[$filename] ?? null;

            if (!$method) {
                $this->warn("No importer for {$filename}, skipping.");
                continue;
            }

            $this->info("Importing {$filename}...");
            $json = file_get_contents($file);
            $data = json_decode($json, true);

            if (!$data || !is_array($data)) {
                $this->warn("  Empty or invalid JSON, skipping.");
                continue;
            }

            $count = $this->$method($data);
            $this->info("  -> {$count} records imported.");
        }

        $this->info('Import completed!');
    }

    private function importProducts(array $data): int
    {
        $count = 0;
        foreach ($data as $item) {
            $modelo = $item['modelo'] ?? $item['id'] ?? null;
            if (!$modelo) continue;

            Product::updateOrCreate(
                ['modelo' => $modelo],
                [
                    'title' => $item['title'] ?? "Invicta {$modelo}",
                    'slug' => $item['slug'] ?? 'invicta-' . Str::slug($modelo),
                    'descripcion' => $item['descripcion'] ?? null,
                    'color' => $item['color'] ?? null,
                    'brazalete' => $item['brazalete'] ?? null,
                    'coleccion' => $item['coleccion'] ?? null,
                    'tipo_movimiento' => $item['tipo_movimiento'] ?? null,
                    'size' => $item['size'] ?? null,
                    'genero' => $item['genero'] ?? null,
                    'caja' => $item['caja'] ?? null,
                    'resistencia_agua' => $item['resistencia_agua'] ?? null,
                    'video' => $item['video'] ?? null,
                    'precio_venta' => $item['precio_venta'] ?? 0,
                    'precio_original' => $item['precio_original'] ?? null,
                    'descuento' => $item['descuento'] ?? 0,
                    'stock' => $item['stock'] ?? 0,
                    'imagen' => $item['imagen'] ?? null,
                    'isGif' => $item['isGif'] ?? false,
                    'activo' => ($item['stock'] ?? 0) > 0,
                    'imagenes_extra' => isset($item['imagenes_extra']) ? (is_array($item['imagenes_extra']) ? $item['imagenes_extra'] : null) : null,
                    'vistas' => $item['vistas'] ?? 0,
                ]
            );
            $count++;
        }
        return $count;
    }

    private function importProductsAlias(array $data): int
    {
        $count = 0;
        foreach ($data as $item) {
            $modelo = $item['modelo'] ?? $item['id'] ?? null;
            if (!$modelo) continue;

            Product::firstOrCreate(
                ['modelo' => $modelo],
                [
                    'title' => $item['title'] ?? "Invicta {$modelo}",
                    'slug' => 'invicta-' . Str::slug($modelo),
                    'precio_venta' => $item['precio_venta'] ?? 0,
                    'stock' => $item['stock'] ?? 0,
                    'imagen' => $item['imagen'] ?? null,
                ]
            );
            $count++;
        }
        return $count;
    }

    private function importInvoices(array $data): int
    {
        $count = 0;
        foreach ($data as $item) {
            Invoice::updateOrCreate(
                ['invoice_number' => $item['invoice_number'] ?? "INV-{$item['id']}"],
                [
                    'client_name' => $item['client_name'] ?? $item['nombre_cliente'] ?? 'Cliente',
                    'client_email' => $item['client_email'] ?? null,
                    'client_phone' => $item['client_phone'] ?? null,
                    'subtotal' => $item['subtotal'] ?? 0,
                    'discount' => $item['discount'] ?? 0,
                    'total' => $item['total'] ?? 0,
                    'status' => $item['status'] ?? 'pending',
                    'notes' => $item['notes'] ?? null,
                ]
            );
            $count++;
        }
        return $count;
    }

    private function importClients(array $data): int
    {
        $count = 0;
        foreach ($data as $item) {
            Client::updateOrCreate(
                ['email' => $item['email'] ?? 'no-email-' . $item['id']],
                [
                    'name' => $item['name'] ?? $item['nombre'] ?? 'Cliente',
                    'phone' => $item['phone'] ?? $item['telefono'] ?? null,
                    'notes' => $item['notes'] ?? null,
                ]
            );
            $count++;
        }
        return $count;
    }

    private function importExpenses(array $data): int
    {
        $count = 0;
        foreach ($data as $item) {
            Expense::create([
                'description' => $item['description'] ?? $item['descripcion'] ?? 'Gasto',
                'amount' => $item['amount'] ?? $item['monto'] ?? 0,
                'category' => $item['category'] ?? $item['categoria'] ?? null,
                'expense_date' => $item['expense_date'] ?? $item['fecha'] ?? now(),
                'notes' => $item['notes'] ?? null,
            ]);
            $count++;
        }
        return $count;
    }

    private function importSubscribers(array $data): int
    {
        $count = 0;
        foreach ($data as $item) {
            $email = $item['email'] ?? $item['correo'] ?? null;
            if (!$email) continue;

            Subscriber::firstOrCreate(
                ['email' => $email],
                ['active' => $item['active'] ?? $item['activo'] ?? true]
            );
            $count++;
        }
        return $count;
    }

    private function importSettings(array $data): int
    {
        $count = 0;
        foreach ($data as $item) {
            $key = $item['key'] ?? $item['id'] ?? null;
            if (!$key) continue;

            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => isset($item['value']) ? (is_array($item['value']) ? json_encode($item['value']) : $item['value']) : null]
            );
            $count++;
        }
        return $count;
    }

    private function importCombos(array $data): int
    {
        $count = 0;
        foreach ($data as $item) {
            Combo::create([
                'name' => $item['name'] ?? $item['nombre'] ?? 'Combo',
                'description' => $item['description'] ?? null,
                'price' => $item['price'] ?? $item['precio'] ?? 0,
                'original_price' => $item['original_price'] ?? null,
                'image' => $item['image'] ?? null,
                'active' => $item['active'] ?? $item['activo'] ?? true,
            ]);
            $count++;
        }
        return $count;
    }

    private function importProductComments(array $data): int
    {
        $count = 0;
        foreach ($data as $item) {
            $product = Product::where('modelo', $item['productModelo'] ?? $item['producto'] ?? null)->first();
            if (!$product) continue;
            ProductComment::create([
                'product_id' => $product->id,
                'author_name' => $item['author_name'] ?? $item['usuario'] ?? 'Anónimo',
                'content' => $item['content'] ?? $item['comentario'] ?? '',
                'rating' => $item['rating'] ?? null,
                'approved' => $item['approved'] ?? true,
            ]);
            $count++;
        }
        return $count;
    }

    private function importSyncLogs(array $data): int
    {
        $count = 0;
        foreach ($data as $item) {
            SyncLog::create([
                'type' => $item['type'] ?? 'stock',
                'status' => $item['status'] ?? 'completed',
                'message' => $item['message'] ?? null,
                'details' => isset($item['details']) ? (is_array($item['details']) ? $item['details'] : null) : null,
            ]);
            $count++;
        }
        return $count;
    }

    private function importMarketingTasks(array $data): int
    {
        $count = 0;
        foreach ($data as $item) {
            MarketingTask::create([
                'title' => $item['title'] ?? $item['titulo'] ?? 'Tarea',
                'description' => $item['description'] ?? null,
                'status' => $item['status'] ?? 'pending',
                'type' => $item['type'] ?? 'general',
                'metadata' => isset($item['metadata']) ? (is_array($item['metadata']) ? $item['metadata'] : null) : null,
            ]);
            $count++;
        }
        return $count;
    }
}
