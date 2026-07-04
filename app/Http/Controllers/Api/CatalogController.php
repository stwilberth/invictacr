<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function facebookCatalog()
    {
        return $this->generateCatalog('Catálogo Invicta Costa Rica', 'Catálogo de relojes Invicta originales para Facebook e Instagram');
    }

    public function whatsappCatalog()
    {
        return $this->generateCatalog('Catálogo Invicta Costa Rica - WhatsApp', 'Catálogo de relojes Invicta originales para WhatsApp Business');
    }

    private function generateCatalog(string $title, string $description)
    {
        try {
            $products = Product::where('activo', true)
                ->where('precio_venta', '>', 0)
                ->get();

            $baseUrl = config('app.url');
            $xmlItems = '';

            foreach ($products as $product) {
                $modelo = preg_replace('/^invicta-/i', '', $product->modelo ?? '');
                $slug = $product->slug;
                $stock = (int) $product->stock;
                $precioVenta = (float) $product->precio_venta;

                $coleccion = $product->coleccion ? trim($product->coleccion) : '';
                $coleccionLabel = $coleccion && strtolower($coleccion) !== 'otros'
                    ? ' ' . ucfirst(mb_strtolower($coleccion))
                    : '';
                $generoLabel = $product->genero
                    ? ' para ' . mb_strtolower($product->genero)
                    : '';
                $sizeLabel = $product->size ? ' - ' . $product->size . ' mm' : '';
                $nombre = "Reloj Invicta{$coleccionLabel}{$generoLabel} ({$modelo}){$sizeLabel}";

                $descripcion = $product->descripcion
                    ?: "Reloj original {$nombre} en Costa Rica."
                    . ($product->caja ? " Caja de {$product->caja}." : '')
                    . ($product->resistencia_agua ? " Resistencia al agua: {$product->resistencia_agua}." : '');

                $imageUrl = $this->getImageUrl($product, $baseUrl);
                $gender = mb_strtolower($product->genero ?? 'unisex');
                $productUrl = "{$baseUrl}/relojes/{$slug}";
                $availability = $stock > 0 ? 'in stock' : 'out of stock';
                $price = number_format($precioVenta, 2, '.', '') . ' CRC';

                $xmlItems .= '
        <item>
            <g:id>' . $this->escapeXml($modelo) . '</g:id>
            <title>' . $this->escapeXml($nombre) . '</title>
            <description>' . $this->escapeXml($descripcion) . '</description>
            <g:link>' . $this->escapeXml($productUrl) . '</g:link>
            <g:image_link>' . $this->escapeXml($imageUrl) . '</g:image_link>
            <g:brand>Invicta</g:brand>
            <g:condition>new</g:condition>
            <g:availability>' . $availability . '</g:availability>
            <g:price>' . $price . '</g:price>
            <g:sale_price>' . $price . '</g:sale_price>
        </item>';
            }

            $xml = '<?xml version="1.0" encoding="UTF-8"?>
<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">
    <channel>
        <title>' . $this->escapeXml($title) . '</title>
        <link>' . $this->escapeXml($baseUrl) . '</link>
        <description>' . $this->escapeXml($description) . '</description>' . $xmlItems . '
    </channel>
</rss>';

            return response($xml, 200)
                ->header('Content-Type', 'application/rss+xml; charset=utf-8')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate');

        } catch (\Exception $error) {
            return response(
                '<?xml version="1.0" encoding="UTF-8"?><error>' . $this->escapeXml($error->getMessage()) . '</error>',
                500
            )->header('Content-Type', 'application/xml');
        }
    }

    private function getImageUrl($product, string $baseUrl): string
    {
        if ($product->imagen) {
            if (str_starts_with($product->imagen, 'http')) {
                return $product->imagen;
            }
            return $baseUrl . $product->imagen;
        }

        $modelo = preg_replace('/^invicta-/i', '', $product->modelo ?? '');
        return "{$baseUrl}/images/relojes/{$modelo}.jpg";
    }

    private function escapeXml(?string $unsafe): string
    {
        if ($unsafe === null || $unsafe === '') {
            return '';
        }

        return htmlspecialchars($unsafe, ENT_XML1 | ENT_QUOTES, 'UTF-8', false);
    }
}
