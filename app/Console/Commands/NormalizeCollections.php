<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class NormalizeCollections extends Command
{
    protected $signature = "invicta:normalize-collections {--dry-run : Solo mostrar los cambios, no escribir}";

    protected $description = "Normaliza las colecciones de products contra la lista canonical de config/collections.php";

    public function handle(): int
    {
        $dry = (bool) $this->option("dry-run");

        $canonical = config("collections");
        if (!is_array($canonical) || empty($canonical)) {
            $this->error("config/collections.php está vacío o no existe.");
            return 1;
        }

        // Mapa: lowercase(canonical) => canonical
        $map = [];
        foreach ($canonical as $name) {
            $map[mb_strtolower(trim($name))] = $name;
        }

        // Alias explícitos (config/collection_aliases.php)
        $aliases = config("collection_aliases", []);
        if (is_array($aliases)) {
            foreach ($aliases as $from => $to) {
                if (isset($map[mb_strtolower($to)])) {
                    $map[mb_strtolower($from)] = $map[mb_strtolower($to)];
                }
            }
        }

        $distinct = Product::whereNotNull("coleccion")
            ->where("coleccion", "!=", "")
            ->select("coleccion")
            ->distinct()
            ->pluck("coleccion")
            ->sortBy(fn($v) => mb_strtolower($v))
            ->values();

        if ($distinct->isEmpty()) {
            $this->info("No hay productos con colección asignada.");
            return 0;
        }

        $changes = [];
        $orphans = [];

        foreach ($distinct as $raw) {
            $clean = trim((string) $raw);
            $clean = preg_replace("/\s+/u", " ", $clean); // colapsa espacios dobles
            $clean = preg_replace('/^(.+)\s+\1$/i', '$1', $clean); // "X X" => "X"
            $key = mb_strtolower($clean);

            if (isset($map[$key])) {
                $target = $map[$key];
                if ($target !== $raw) {
                    $changes[] = ["from" => $raw, "to" => $target];
                }
            } else {
                $orphans[] = $raw;
            }
        }

        if ($changes) {
            $this->table(["Actual", "=> Canonical"], $changes);
            $this->line(
                sprintf("  %d valor(es) a normalizar.", count($changes)),
            );
        } else {
            $this->info(
                "No hay valores que normalizar (todos ya son canónicos).",
            );
        }

        if ($orphans) {
            $this->newLine();
            $this->warn(
                "Valores SIN equivalente en la lista canonical (revisar manualmente):",
            );
            foreach ($orphans as $o) {
                $this->line("  • " . $o);
            }
            $this->line(
                "  Agrega el nombre correcto a config/collections.php o corrige el producto.",
            );
        }

        if ($dry || empty($changes)) {
            if ($dry) {
                $this->newLine();
                $this->comment(
                    "Modo --dry-run: no se modificó la base de datos.",
                );
            }
            return 0;
        }

        if (
            !$this->confirm(
                "¿Aplicar la normalización en la base de datos de producción?",
                true,
            )
        ) {
            $this->info("Cancelado. No se modificó nada.");
            return 0;
        }

        DB::transaction(function () use ($changes) {
            foreach ($changes as $c) {
                $updated = Product::where("coleccion", $c["from"])->update([
                    "coleccion" => $c["to"],
                ]);
                $this->line(
                    sprintf(
                        "  %s => %s (%d filas)",
                        $c["from"],
                        $c["to"],
                        $updated,
                    ),
                );
            }
        });

        $this->newLine();
        $this->info("Normalización completada.");
        return 0;
    }
}
