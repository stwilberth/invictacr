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

    /**
     * Feed de catálogo para Pinterest (formato RSS 2.0 + namespace Google Shopping,
     * compatible con los catálogos de Pinterest).
     * URL pública: /pinterest-feed.xml
     */
    public function pinterestCatalog()
    {
        try {
            $products = Product::where('activo', true)
                ->where('precio_venta', '>', 0)
                ->where('stock', '>', 0)
                ->where('disponibilidad', '!=', 'agotado')
                ->where('proximo', false)
                ->with('images')
                ->cursor();

            $baseUrl = 'https://invictacostarica.com';
            $xmlItems = '';

            foreach ($products as $product) {
                $modelo = preg_replace('/^invicta-/i', '', $product->modelo ?? '');
                $id = $modelo !== '' ? $modelo : $product->slug;
                $stock = (int) $product->stock;
                $precioVenta = (float) $product->precio_venta;
                $descuento = (int) ($product->descuento ?? 0);
                $precioFinal = $descuento > 0
                    ? $precioVenta * (1 - $descuento / 100)
                    : $precioVenta;

                $nombre = mb_substr($product->display_title, 0, 200);

                $descripcion = $product->descripcion
                    ?: "Reloj original {$nombre} en Costa Rica."
                        . ($product->caja ? " Caja de {$product->caja}." : '')
                        . ($product->resistencia_agua ? " Resistencia al agua: {$product->resistencia_agua}." : '');
                $descripcion = trim(preg_replace('/\s+/', ' ', strip_tags($descripcion)));
                $descripcion = mb_substr($descripcion, 0, 1000);

                $imageUrl = $this->getImageUrl($product, $baseUrl);
                $productUrl = route('products.show', ['slug' => $product->slug]);

                if (!empty($product->proximo)) {
                    $availability = 'preorder';
                } else {
                    $availability = $stock > 0 && ($product->disponibilidad ?? 'disponible') !== 'agotado'
                        ? 'in_stock'
                        : 'out_of_stock';
                }

                $price = number_format($precioFinal, 2, '.', '') . ' CRC';

                $genero = mb_strtolower($product->genero ?? 'unisex');
                $pinterestGender = match ($genero) {
                    'hombre' => 'male',
                    'mujer' => 'female',
                    default => 'unisex',
                };

                $coleccionLabel = $product->coleccion && mb_strtolower(trim($product->coleccion)) !== 'otros'
                    ? ucfirst(mb_strtolower(trim($product->coleccion)))
                    : 'Invicta';
                $productType = "Relojes > {$coleccionLabel} > " . ucfirst($genero);

                $xmlItems .= '
        <item>
            <g:id>' . $this->escapeXml($id) . '</g:id>
            <title>' . $this->escapeXml($nombre) . '</title>
            <description>' . $this->escapeXml($descripcion) . '</description>
            <g:link>' . $this->escapeXml($productUrl) . '</g:link>
            <g:image_link>' . $this->escapeXml($imageUrl) . '</g:image_link>';

                foreach ($product->images->take(10) as $extra) {
                    $extraUrl = str_starts_with($extra->url, 'http') ? $extra->url : $baseUrl . $extra->url;
                    if ($extraUrl !== $imageUrl) {
                        $xmlItems .= '
            <g:additional_image_link>' . $this->escapeXml($extraUrl) . '</g:additional_image_link>';
                    }
                }

                $xmlItems .= '
            <g:brand>Invicta</g:brand>
            <g:condition>new</g:condition>
            <g:availability>' . $availability . '</g:availability>
            <g:price>' . $price . '</g:price>';

                if ($descuento > 0) {
                    $xmlItems .= '
            <g:sale_price>' . $price . '</g:sale_price>';
                }

                $xmlItems .= '
            <g:mpn>' . $this->escapeXml($product->modelo ?? $id) . '</g:mpn>
            <g:gender>' . $pinterestGender . '</g:gender>
            <g:google_product_category>Apparel &amp; Accessories &gt; Jewelry &gt; Watches</g:google_product_category>
            <g:product_type>' . $this->escapeXml($productType) . '</g:product_type>
            <g:shipping>
                <g:country>CR</g:country>
                <g:service>Standard</g:service>
                <g:price>0.00 CRC</g:price>
            </g:shipping>
        </item>';
            }

            $xml = '<?xml version="1.0" encoding="UTF-8"?>
<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">
    <channel>
        <title>' . $this->escapeXml('Invicta Costa Rica - Catálogo Pinterest') . '</title>
        <link>' . $this->escapeXml($baseUrl) . '</link>
        <description>' . $this->escapeXml('Catálogo de relojes Invicta 100% originales para Pinterest. Envío gratis en GAM, pago contra entrega y garantía de 6 meses.') . '</description>' . $xmlItems . '
    </channel>
</rss>';

            return response($xml, 200)
                ->header('Content-Type', 'application/rss+xml; charset=utf-8')
                ->header('Cache-Control', 'public, max-age=1800');

        } catch (\Exception $error) {
            return response(
                '<?xml version="1.0" encoding="UTF-8"?><error>' . $this->escapeXml($error->getMessage()) . '</error>',
                500
            )->header('Content-Type', 'application/xml');
        }
    }

    private function generateCatalog(string $title, string $description)
    {
        try {
            $products = Product::where('activo', true)
                ->where('precio_venta', '>', 0)
                ->get();

            $baseUrl = 'https://invictacostarica.com';
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
                $availability = $stock > 0 && ($product->disponibilidad ?? 'disponible') !== 'agotado' ? 'in stock' : 'out of stock';
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
