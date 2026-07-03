<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class NormalizeMovimiento extends Command
{
    protected $signature = "invicta:normalize-movimiento {--dry-run : Solo mostrar los cambios, no escribir}";

    protected $description = "Normaliza tipo_movimiento a solo 'cuarzo' o 'automatico'";

    public function handle(): int
    {
        $dry = (bool) $this->option("dry-run");

        $distinct = Product::whereNotNull("tipo_movimiento")
            ->where("tipo_movimiento", "!=", "")
            ->select("tipo_movimiento")
            ->distinct()
            ->pluck("tipo_movimiento")
            ->sort()
            ->values();

        if ($distinct->isEmpty()) {
            $this->info("No hay productos con tipo_movimiento asignado.");
            return 0;
        }

        $changes = [];

        foreach ($distinct as $raw) {
            $normalized = Product::normalizeMovimiento($raw);
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
                $updated = Product::where("tipo_movimiento", $c["from"])->update([
                    "tipo_movimiento" => $c["to"],
                ]);
                $this->line(sprintf("  %s => %s (%d filas)", $c["from"], $c["to"], $updated));
            }
        });

        $this->newLine();
        $this->info("Normalización completada.");
        return 0;
    }
}
