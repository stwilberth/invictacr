<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class InvictaWatchScraper
{
    const BASE_URL = "https://www.invictawatch.com";
    const CDN_URL = "https://www.invictawatch.com/storage/products";

    private const HEADERS = [
        "User-Agent" => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36",
        "Accept" => "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
        "Accept-Language" => "es-CR,es;q=0.9,en;q=0.8",
    ];

    public function scrape(string $modelo): ?array
    {
        $url = self::BASE_URL . "/watches/detail/{$modelo}";

        $response = Http::withHeaders(self::HEADERS)
            ->timeout(30)
            ->get($url);

        if (!$response->ok()) {
            return null;
        }

        $html = $response->body();

        $product = $this->parseProductJson($html, $modelo);
        if (!$product) {
            $meta = $this->parseMetaTags($html);
            if (!$meta) {
                return null;
            }
            $product = [
                "name" => $meta["name"] ?? "",
                "description" => $meta["description"] ?? "",
                "collection_name" => null,
                "gender" => null,
                "tile_image" => $meta["image"] ?? "",
                "images" => [],
            ];
        }

        $specs = $this->parseSpecs($html, $modelo);
        $msrp = $this->parseMsrp($html);

        $title = $this->cleanTitle($product["name"] ?? "");
        $descripcion = $product["description"] ?? "";
        $imageUrl = $product["tile_image"] ?? ($product["images"][0]["urlsBySize"]["l"] ?? "");

        $imagePath = $this->downloadImage($modelo, $imageUrl);

        $band = $specs["Band"] ?? [];
        $case = $specs["Case and Dial"] ?? [];
        $movement = $specs["Movement"] ?? [];
        $water = $specs["Water Resistance"] ?? [];

        $size = $this->extractNumber($this->specValue($case, "Case Size"));
        $caja = $this->specValue($case, "Case Material");
        $brazalete = $this->specValue($band, "Material");
        $tone = $this->specValue($band, "Tone");
        $movimientoRaw = $this->specValue($movement, "Caliber");
        $resistenciaAgua = $this->extractNumber($this->specValue($water, "Water Resistance"));

        $color = $this->detectColor($title, $descripcion, $brazalete, $tone, $case, $band);

        return [
            "title" => $title,
            "descripcion" => $descripcion,
            "imagen_url" => $imageUrl,
            "imagen_local" => $imagePath,
            "coleccion" => $product["collection_name"] ?? null,
            "genero" => $this->detectGender($title, $descripcion, $product["gender"] ?? null),
            "color" => $color,
            "msrp" => $msrp,
            "size" => $size,
            "caja" => $caja,
            "brazalete" => $brazalete,
            "tipo_movimiento" => Product::normalizeMovimiento($movimientoRaw),
            "movimiento_raw" => $movimientoRaw,
            "resistencia_agua" => $resistenciaAgua,
        ];
    }

    /**
     * El sitio incrusta el producto como JSON en el atributo Vue :product="..." (HTML-encoded).
     * Se prefiere el JSON cuyo model_no coincide; si no, el primero con imágenes.
     */
    private function parseProductJson(string $html, string $modelo): ?array
    {
        $found = null;

        if (preg_match_all('/:product="([^"]+)"/s', $html, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $m) {
                $json = html_entity_decode($m[1], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $data = json_decode($json, true);
                if (!is_array($data)) {
                    continue;
                }

                $modelNo = (string) ($data["model_no"] ?? "");
                $hasImages = isset($data["images"]) && is_array($data["images"]) && count($data["images"]) > 0;

                if ($modelNo === $modelo) {
                    return $data;
                }

                if ($found === null && $hasImages) {
                    $found = $data;
                }
            }
        }

        return $found;
    }

    /**
     * Fallback: meta tags SEO (og:title / og:description / og:image).
     */
    private function parseMetaTags(string $html): ?array
    {
        $meta = [];
        if (preg_match('/<meta[^>]*(?:property="og:title"|name="title")[^>]*content="([^"]*)"/i', $html, $m)) {
            $meta["name"] = trim($m[1]);
        }
        if (preg_match('/<meta[^>]*(?:property="og:description"|name="description")[^>]*content="([^"]*)"/i', $html, $m)) {
            $meta["description"] = trim($m[1]);
        }
        if (preg_match('/<meta[^>]*property="og:image"[^>]*content="([^"]*)"/i', $html, $m)) {
            $meta["image"] = trim($m[1]);
        }
        return !empty($meta) ? $meta : null;
    }

    /**
     * Extrae las specs del tab de especificaciones: tabla con <th> sección y <li>Clave: Valor</li>.
     */
    private function parseSpecs(string $html, string $modelo): array
    {
        $specs = [];

        if (!preg_match('/data-tab="specs-tab-' . preg_quote($modelo, '/') . '"(.*?)<\/table>/is', $html, $m)) {
            return $specs;
        }

        if (!preg_match_all('/<tr>\s*<th>(.*?)<\/th>\s*<td>(.*?)<\/td>\s*<\/tr>/is', $m[1], $rows, PREG_SET_ORDER)) {
            return $specs;
        }

        foreach ($rows as $row) {
            $section = trim(html_entity_decode(strip_tags($row[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8'));

            preg_match_all('/<li>(.*?)<\/li>/is', $row[2], $items);

            $map = [];
            foreach ($items[1] as $item) {
                $text = trim(html_entity_decode(strip_tags($item), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
                $text = preg_replace('/[\s\xC2\xA0]+/u', ' ', $text);

                if ($text !== '' && preg_match('/^([^:]+):\s*(.*)$/', $text, $pm)) {
                    $key = trim($pm[1]);
                    $map[$key] = $map[$key] ?? trim($pm[2]);
                }
            }

            if ($section !== '') {
                $specs[$section] = $map;
            }
        }

        return $specs;
    }

    private function specValue(array $map, string $key): ?string
    {
        if (isset($map[$key]) && trim($map[$key]) !== '') {
            return trim($map[$key]);
        }
        return null;
    }

    private function extractNumber(?string $value): ?string
    {
        if (is_null($value) || trim($value) === '') {
            return null;
        }
        $cleaned = preg_replace('/[^0-9.,]/', '', trim($value));
        $cleaned = str_replace(',', '.', $cleaned);
        $cleaned = preg_replace('/\.(?=.*\.)/', '', $cleaned);
        return trim($cleaned) !== '' ? $cleaned : null;
    }

    private function cleanTitle(string $name): string
    {
        $name = trim(preg_replace('/\s+/u', ' ', $name));
        if (preg_match('/^\d+\s*-\s*(.+)$/', $name, $m)) {
            $name = trim($m[1]);
        }
        return $name ?: "";
    }

    private function parseMsrp(string $html): ?int
    {
        $html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        if (preg_match('/MSRP\s*\$?\s*([\d,]+)/i', $html, $m)) {
            return (int) str_replace(",", "", $m[1]);
        }
        return null;
    }

    private function detectColor(string $title, string $description, ?string $bandMaterial, ?string $bandTone, array $caseSpecs, array $bandSpecs): ?string
    {
        // El campo color = color del brazalete: prioriza el "Tone" de la sección Band.
        if ($bandTone !== null && trim($bandTone) !== '') {
            $match = $this->matchColor($bandTone);
            if ($match !== null) {
                return $match;
            }
        }

        // Fallback: material del brazalete.
        if ($bandMaterial !== null && trim($bandMaterial) !== '') {
            $match = $this->matchColor($bandMaterial);
            if ($match !== null) {
                return $match;
            }
        }

        // Fallback: colores de specs (Bezel/Dial/Case).
        $priority = ["Bezel Color", "Case Color", "Dial Color", "Band Color"];
        $specs = $caseSpecs + $bandSpecs;
        foreach ($priority as $key) {
            if (isset($specs[$key])) {
                $match = $this->matchColor($specs[$key]);
                if ($match !== null) {
                    return $match;
                }
            }
        }

        // Último recurso: título + descripción.
        return $this->matchColor($title . " " . $description);
    }

    private function matchColor(string $text): ?string
    {
        $text = mb_strtolower(html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        if ($text === '') {
            return null;
        }

        $rules = [
            "Oro Rosa" => ["rose gold", "or rosa", "pink gold", "pink"],
            "Plateado Dorado" => ["two-tone", "two tone", "bicolor", "combinado", "gold and silver"],
            "Titanio" => ["titanium", "titanio"],
            "Azul" => ["blue", "azul"],
            "Negro" => ["black", "negro"],
            "Blanco" => ["white", "blanco"],
            "Verde" => ["green", "verde"],
            "Rojo" => ["red", "rojo", "burgundy"],
            "Gris Oscuro" => ["grey", "gray", "gris"],
            "Dorado" => ["gold", "yellow", "dorado", "amarillo"],
            "Plateado" => ["silver", "chrome", "plata", "plateado", "steel", "stainless", "acero"],
        ];

        foreach ($rules as $canonical => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($text, $keyword)) {
                    return $canonical;
                }
            }
        }

        return null;
    }

    private function detectGender(string $title, string $description, ?string $productGender): ?string
    {
        if ($productGender) {
            $g = mb_strtolower($productGender);
            if (str_contains($g, 'women') || str_contains($g, 'lady') || str_contains($g, 'ladies')) {
                return "mujer";
            }
            if (str_contains($g, 'men')) {
                return "hombre";
            }
        }

        $text = $title . " " . $description;
        if (preg_match('/\bWomen\b|\bLad(?:y|ies)\b|\bDamen\b/i', $text)) {
            return "mujer";
        }
        if (preg_match('/\bMen\b/i', $text)) {
            return "hombre";
        }
        return null;
    }

    private function downloadImage(string $modelo, string $imageUrl): ?string
    {
        $urlsToTry = [];

        if (!empty($imageUrl)) {
            $urlsToTry[] = $imageUrl;
        }

        // Fallback: patrón de storage del sitio.
        $urlsToTry[] = self::CDN_URL . "/{$modelo}/catalogshot_m.webp";

        foreach ($urlsToTry as $url) {
            try {
                $response = Http::withHeaders(self::HEADERS)
                    ->timeout(30)
                    ->get($url);

                if (!$response->ok()) {
                    continue;
                }

                $ext = $this->detectExtension($response->header("Content-Type"), $url);
                $filename = "{$modelo}.{$ext}";
                $path = "relojes/{$filename}";

                Storage::disk('r2')->put($path, $response->body(), 'public');

                return "/storage/{$path}";
            } catch (\Exception $e) {
                continue;
            }
        }

        return null;
    }

    private function detectExtension(?string $contentType, string $url): string
    {
        $ct = strtolower((string) $contentType);
        if (str_contains($ct, 'webp')) {
            return 'webp';
        }
        if (str_contains($ct, 'png')) {
            return 'png';
        }
        if (str_contains($ct, 'jpeg') || str_contains($ct, 'jpg')) {
            return 'jpg';
        }

        $url = preg_replace('/\?.*$/', '', $url);
        if (preg_match('/\.(webp|png|jpe?g|gif)$/i', $url, $m)) {
            return $m[1] === 'jpeg' ? 'jpg' : strtolower($m[1]);
        }

        return 'jpg';
    }
}
