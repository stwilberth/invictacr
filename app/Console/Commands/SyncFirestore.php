<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Abono;
use App\Models\Expense;
use App\Models\Subscriber;
use App\Models\Setting;
use App\Models\Combo;
use App\Models\MarketingTask;
use App\Models\SyncLog;
use App\Models\ProductComment;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SyncFirestore extends Command
{
    protected $signature = "firestore:sync {--fresh : Truncate all tables before importing}";
    protected $description = "Sync data from Firestore JSON exports to MariaDB";

    private string $exportDir;

    private array $importers = [
        "products" => "importProducts",
        "productos" => "importProductsAlias",
        "invoices" => "importInvoices",
        "clients" => "importClients",
        "expenses" => "importExpenses",
        "suscriptores" => "importSubscribers",
        "settings" => "importSettings",
        "combos" => "importCombos",
        "marketing_tasks" => "importMarketingTasks",
        "product_comments" => "importProductComments",
        "sync_logs" => "importSyncLogs",
    ];

    public function handle()
    {
        $this->exportDir =
            dirname(base_path(), 1) . "/invictacostarica/firebase-export";

        if (!is_dir($this->exportDir)) {
            $this->error(
                "Firebase export directory not found at: {$this->exportDir}",
            );
            $this->warn(
                "Make sure the invictacostarica (Astro) project is cloned alongside this one.",
            );
            return 1;
        }

        if ($this->option("fresh")) {
            $this->truncateAll();
        }

        $files = glob("{$this->exportDir}/*.json");
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

        $this->normalizeImages();

        $this->info("Sync completed!");
    }

    private function truncateAll(): void
    {
        $this->info("Truncating all tables...");
        DB::statement("SET foreign_key_checks = 0");
        $tables = [
            "products",
            "clients",
            "invoices",
            "invoice_items",
            "abonos",
            "expenses",
            "subscribers",
            "settings",
            "combos",
            "marketing_tasks",
            "sync_logs",
            "product_comments",
            "product_images",
            "categories",
        ];
        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }
        DB::statement("SET foreign_key_checks = 1");
        $this->info("All tables truncated.");
    }

    private function normalizeImages(): void
    {
        $this->info("Normalizing image paths...");

        // Fix ../../assets/relojes/ → /assets/relojes/
        $fixed = DB::update(
            "UPDATE products SET imagen = REPLACE(imagen, '../../assets/relojes/', '/assets/relojes/') WHERE imagen LIKE '%../../assets/relojes/%'",
        );
        $this->line(
            "  Fixed relative paths (../../assets → /assets): {$fixed}",
        );

        // Fix CDN URLs → local path
        $cdnRows = DB::select(
            "SELECT id, modelo FROM products WHERE imagen LIKE 'https://cdn.invictawatch.com/%'",
        );
        $count = 0;
        foreach ($cdnRows as $row) {
            $model = preg_replace("/^invicta-/i", "", $row->modelo ?? "");
            DB::update("UPDATE products SET imagen = ? WHERE id = ?", [
                "/assets/relojes/{$model}.jpg",
                $row->id,
            ]);
            $count++;
        }
        $this->line("  Fixed CDN URLs: {$count}");

        // Fix empty images
        $emptyRows = DB::select(
            "SELECT id, modelo FROM products WHERE (imagen IS NULL OR imagen = '') AND modelo IS NOT NULL AND modelo != ''",
        );
        $count = 0;
        foreach ($emptyRows as $row) {
            $model = preg_replace("/^invicta-/i", "", $row->modelo ?? "");
            DB::update("UPDATE products SET imagen = ? WHERE id = ?", [
                "/assets/relojes/{$model}.jpg",
                $row->id,
            ]);
            $count++;
        }
        $this->line("  Fixed empty images: {$count}");
    }

    private function importProducts(array $data): int
    {
        $count = 0;
        foreach ($data as $item) {
            $modelo = $item["modelo"] ?? ($item["id"] ?? null);
            if (!$modelo) {
                continue;
            }

            $product = Product::updateOrCreate(
                ["modelo" => $modelo],
                $this->mapProductFields($item),
            );

            $this->syncExtraImages($product, $item);

            $count++;
        }
        return $count;
    }

    private function syncExtraImages(Product $product, array $item): void
    {
        $extra = $item["imagenes_extra"] ?? null;
        if (!$extra) {
            return;
        }

        $urls = is_array($extra) ? $extra : (json_decode($extra, true) ?? []);
        if (empty($urls)) {
            return;
        }

        $product->images()->delete();
        foreach ($urls as $order => $url) {
            $product->images()->create([
                'url' => $url,
                'order' => $order,
                'type' => 'image',
            ]);
        }
    }

    private function importProductsAlias(array $data): int
    {
        $count = 0;
        foreach ($data as $item) {
            $modelo = $item["modelo"] ?? ($item["id"] ?? null);
            if (!$modelo) {
                continue;
            }

            $product = Product::firstOrCreate(
                ["modelo" => $modelo],
                $this->mapProductFields($item),
            );

            $this->syncExtraImages($product, $item);

            $count++;
        }
        return $count;
    }

    private function mapProductFields(array $item): array
    {
        $modelo = $item["modelo"] ?? ($item["id"] ?? "unknown");
        return [
            "title" => $item["title"] ?? "Invicta {$modelo}",
            "slug" => $item["slug"] ?? "invicta-" . Str::slug($modelo),
            "descripcion" => $item["descripcion"] ?? null,
            "color" => $item["color"] ?? null,
            "brazalete" => $item["brazalete"] ?? null,
            "coleccion" => $item["coleccion"] ?? null,
            "tipo_movimiento" => $item["tipo_movimiento"] ?? null,
            "size" => $item["size"] ?? null,
            "genero" => $item["genero"] ?? null,
            "caja" => $item["caja"] ?? null,
            "resistencia_agua" => $item["resistencia_agua"] ?? null,
            "video" => $item["video"] ?? null,
            "precio_venta" => $item["precio_venta"] ?? 0,
            "precio_original" => $item["precio_original"] ?? null,
            "descuento" => $item["descuento"] ?? 0,
            "stock" => $item["stock"] ?? 0,
            "imagen" => $item["imagen"] ?? null,
            "activo" => ($item["stock"] ?? 0) > 0,
            "vistas" => $item["vistas"] ?? 0,
        ];
    }

    private function importInvoices(array $data): int
    {
        $count = 0;
        foreach ($data as $item) {
            $invoiceNumber = $item["invoice_number"] ?? "INV-{$item["id"]}";

            $invoice = Invoice::updateOrCreate(
                [
                    "invoice_number" => $invoiceNumber,
                ],
                [
                    "client_name" =>
                        $item["customerName"] ??
                        ($item["client_name"] ??
                            ($item["nombre_cliente"] ?? "Cliente")),
                    "client_email" => $item["client_email"] ?? null,
                    "client_phone" =>
                        $item["customerPhone"] ??
                        ($item["client_phone"] ?? null),
                    "customer_address" =>
                        $item["customerAddress"] ?? null,
                    "subtotal" => $item["subtotal"] ?? 0,
                    "discount" => $item["discount"] ?? 0,
                    "shipping" => $item["shipping"] ?? 0,
                    "shipping_cost" => $item["shippingCost"] ?? null,
                    "total" => $item["total"] ?? 0,
                    "status" => $item["status"] ?? "pending",
                    "shipping_status" => $item["shippingStatus"] ?? "pendiente",
                    "notes" => $item["notes"] ?? null,
                    "delivery_date" => $this->emptyToNull($item["deliveryDate"] ?? null),
                    "delivery_time_start" => $this->emptyToNull($item["deliveryTimeStart"] ?? null),
                    "delivery_time_end" => $this->emptyToNull($item["deliveryTimeEnd"] ?? null),
                    "location" => $this->emptyToNull($item["location"] ?? null),
                    "needs_bracelet_adjustment" =>
                        $item["needsBraceletAdjustment"] ?? false,
                    "creation_date" => $this->emptyToNull($item["creationDate"] ?? null),
                    "estimated_utility" => $item["estimatedUtility"] ?? null,
                    "cedula" => $this->emptyToNull($item["cedula"] ?? null),
                    "issued_at" => isset($item["createdAt"])
                        ? $this->parseFirestoreTimestamp($item["createdAt"])
                        : null,
                ],
            );

            if (isset($item["createdAt"])) {
                $createdAt = $this->parseFirestoreTimestamp($item["createdAt"]);
                $invoice->timestamps = false;
                $invoice->created_at = $createdAt;
                if (isset($item["updatedAt"])) {
                    $invoice->updated_at = $this->parseFirestoreTimestamp($item["updatedAt"]);
                }
                $invoice->save();
                $invoice->timestamps = true;
            }

            $this->importInvoiceItems($invoice, $item["items"] ?? []);
            $this->importAbonos($invoice, $item["abonos"] ?? []);

            $count++;
        }
        return $count;
    }

    private function importInvoiceItems(Invoice $invoice, array $items): void
    {
        $invoice->items()->delete();
        foreach ($items as $order => $item) {
            $model = $item["model"] ?? null;
            $product = $model
                ? Product::where("modelo", $model)->first()
                : null;

            InvoiceItem::create([
                "invoice_id" => $invoice->id,
                "product_id" => $product->id ?? null,
                "product_name" =>
                    $item["name"] ?? "Producto {$item["model"]}",
                "product_model" => $item["model"] ?? null,
                "quantity" => $item["quantity"] ?? 1,
                "unit_price" => $item["price"] ?? 0,
                "subtotal" => $item["total"] ?? 0,
            ]);
        }
    }

    private function importAbonos(Invoice $invoice, array $abonos): void
    {
        $invoice->abonos()->delete();
        foreach ($abonos as $abono) {
            Abono::create([
                "invoice_id" => $invoice->id,
                "amount" => $abono["amount"] ?? 0,
                "date" => isset($abono["date"])
                    ? Carbon::parse($abono["date"])
                    : null,
                "note" => $abono["note"] ?? null,
            ]);
        }
    }

    private function emptyToNull(mixed $value): mixed
    {
        return ($value === "" || $value === "———") ? null : $value;
    }

    private function parseFirestoreTimestamp(array $ts): ?Carbon
    {
        if (!isset($ts["_seconds"])) {
            return null;
        }
        return Carbon::createFromTimestamp($ts["_seconds"]);
    }

    private function importClients(array $data): int
    {
        $count = 0;
        foreach ($data as $item) {
            $phone = $item["phone"] ?? ($item["telefono"] ?? null);

            $client = Client::updateOrCreate(
                [
                    "phone" => $phone ?: "no-phone-" . ($item["id"] ?? uniqid()),
                ],
                [
                    "name" => $item["name"] ?? ($item["nombre"] ?? "Cliente"),
                    "email" => $this->emptyToNull($item["email"] ?? null),
                    "address" => $this->emptyToNull($item["address"] ?? null),
                    "notes" => $item["notes"] ?? null,
                ],
            );

            if (isset($item["createdAt"])) {
                $createdAt = $this->parseFirestoreTimestamp($item["createdAt"]);
                $client->timestamps = false;
                $client->created_at = $createdAt;
                if (isset($item["updatedAt"])) {
                    $client->updated_at = $this->parseFirestoreTimestamp($item["updatedAt"]);
                }
                $client->save();
                $client->timestamps = true;
            }

            $count++;
        }
        return $count;
    }

    private function importExpenses(array $data): int
    {
        $count = 0;
        foreach ($data as $item) {
            Expense::create([
                "description" =>
                    $item["description"] ?? ($item["descripcion"] ?? "Gasto"),
                "amount" => $item["amount"] ?? ($item["monto"] ?? 0),
                "category" => $item["category"] ?? ($item["categoria"] ?? null),
                "expense_date" =>
                    $item["expense_date"] ?? ($item["fecha"] ?? now()),
                "notes" => $item["notes"] ?? null,
            ]);
            $count++;
        }
        return $count;
    }

    private function importSubscribers(array $data): int
    {
        $count = 0;
        foreach ($data as $item) {
            $email = $item["email"] ?? ($item["correo"] ?? null);
            if (!$email) {
                continue;
            }

            Subscriber::firstOrCreate(
                ["email" => $email],
                ["active" => $item["active"] ?? ($item["activo"] ?? true)],
            );
            $count++;
        }
        return $count;
    }

    private function importSettings(array $data): int
    {
        $count = 0;
        foreach ($data as $item) {
            $key = $item["key"] ?? ($item["id"] ?? null);
            if (!$key) {
                continue;
            }

            Setting::updateOrCreate(
                ["key" => $key],
                [
                    "value" => isset($item["value"])
                        ? (is_array($item["value"])
                            ? json_encode($item["value"])
                            : $item["value"])
                        : null,
                ],
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
                "name" => $item["name"] ?? ($item["nombre"] ?? "Combo"),
                "description" => $item["description"] ?? null,
                "price" => $item["price"] ?? ($item["precio"] ?? 0),
                "original_price" => $item["original_price"] ?? null,
                "image" => $item["image"] ?? null,
                "active" => $item["active"] ?? ($item["activo"] ?? true),
            ]);
            $count++;
        }
        return $count;
    }

    private function importProductComments(array $data): int
    {
        $count = 0;
        foreach ($data as $item) {
            $product = Product::where(
                "modelo",
                $item["productModelo"] ?? ($item["producto"] ?? null),
            )->first();
            if (!$product) {
                continue;
            }

            ProductComment::create([
                "product_id" => $product->id,
                "author_name" =>
                    $item["author_name"] ?? ($item["usuario"] ?? "Anónimo"),
                "content" => $item["content"] ?? ($item["comentario"] ?? ""),
                "rating" => $item["rating"] ?? null,
                "approved" => $item["approved"] ?? true,
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
                "type" => $item["type"] ?? "stock",
                "status" => $item["status"] ?? "completed",
                "message" => $item["message"] ?? null,
                "details" => isset($item["details"])
                    ? (is_array($item["details"])
                        ? json_encode($item["details"])
                        : $item["details"])
                    : null,
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
                "title" => $item["title"] ?? ($item["titulo"] ?? "Tarea"),
                "description" => $item["description"] ?? null,
                "status" => $item["status"] ?? "pending",
                "type" => $item["type"] ?? "general",
                "metadata" => isset($item["metadata"])
                    ? (is_array($item["metadata"])
                        ? json_encode($item["metadata"])
                        : $item["metadata"])
                    : null,
            ]);
            $count++;
        }
        return $count;
    }
}
