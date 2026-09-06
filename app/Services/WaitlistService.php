<?php

namespace App\Services;

use App\Models\Product;
use App\Models\WaitlistEntry;
use App\Models\WaitlistNotification;

class WaitlistService
{
    /**
     * Verifica si el producto quedó disponible y, en ese caso,
     * marca como notificadas las entradas en espera de ese modelo
     * y crea una notificación por cada contacto.
     *
     * Condición: stock positivo + disponibilidad distinta de "agotado" + activo.
     *
     * @return int cantidad de contactos notificados
     */
    public function checkAndNotify(Product $product): int
    {
        $product = $product->fresh() ?? $product;

        if (!$this->isAvailable($product)) {
            return 0;
        }

        $modelo = WaitlistEntry::normalizeModelo($product->modelo);

        $pendientes = WaitlistEntry::where('modelo', $modelo)
            ->where('estado', WaitlistEntry::ESTADO_PENDIENTE)
            ->get();

        if ($pendientes->isEmpty()) {
            return 0;
        }

        $count = 0;
        foreach ($pendientes as $entry) {
            $entry->update([
                'estado' => WaitlistEntry::ESTADO_NOTIFICADO,
                'notified_at' => now(),
            ]);

            WaitlistNotification::create([
                'waitlist_entry_id' => $entry->id,
                'modelo' => $modelo,
                'titulo' => "Reloj {$modelo} disponible",
                'mensaje' => "El modelo {$modelo} quedó con stock ({$product->stock} uds). Contactar a {$entry->nombre}"
                    . ($entry->telefono ? " ({$entry->telefono})" : '') . '.',
            ]);

            $count++;
        }

        return $count;
    }

    public function isAvailable(Product $product): bool
    {
        if ((int) ($product->stock ?? 0) <= 0) {
            return false;
        }

        if (!(bool) ($product->activo ?? false)) {
            return false;
        }

        $disp = mb_strtolower(trim((string) ($product->disponibilidad ?? 'disponible')));

        return $disp !== 'agotado';
    }
}
