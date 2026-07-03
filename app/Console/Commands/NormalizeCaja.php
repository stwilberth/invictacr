<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class NormalizeCaja extends Command
{
    protected $signature = "invicta:normalize-caja {--dry-run : Solo mostrar los cambios, no escribir}";

    protected $description = "Normaliza caja (material) a los valores permitidos: Acero Inoxidable, Silicona, Titanio, Plastico";

    public function handle(): int
    {
        $dry = (bool) $this->option("dry-run");

        $distinct = Product::whereNotNull("caja")
            ->where("caja", "!=", "")
            ->select("caja")
            ->distinct()
            ->pluck("caja")
            ->sort()
            ->values();

        if ($distinct->isEmpty()) {
            $this->info("No hay productos con caja asignada.");
            return 0;
        }

        $changes = [];

        foreach ($distinct as $raw) {
            $normalized = Product::normalizeCaja($raw);
            if ($normalized !== $raw) {
                $changes[] = ["from" => $raw, "to" => $normalized];
            }
        }

        if ($changes) {
            $this->table(["Actual", "=> Normalizado"], $changes);
            $this->line(sprintf("  %d valor(es) a normalizar.", count($changes)));
        } else {
            $this->info("No hay valores que normalizar (todos ya son válidos).");
        }

        if ($dry || empty($changes)) {
            if ($dry) {
                $this->newLine();
                $this->comment("Modo --dry-run: no se modificó la base de datos.");
            }
            return 0;
        }

        if (!$this->confirm("¿Aplicar la normalización en la base de datos?", true)) {
            $this->info("Cancelado. No se modificó nada.");
            return 0;
        }

        DB::transaction(function () use ($changes) {
            foreach ($changes as $c) {
                $updated = Product::where("caja", $c["from"])->update([
                    "caja" => $c["to"],
                ]);
                $this->line(sprintf("  %s => %s (%d filas)", $c["from"], $c["to"], $updated));
            }
        });

        $this->newLine();
        $this->info("Normalización completada.");
        return 0;
    }
}
