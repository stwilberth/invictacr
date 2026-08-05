<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class InvictaWatchScraper
{
    const BASE_URL = "https://www.invictawatch.com";
    const CDN_URL = "https://cdn.invictawatch.com/www/img/products";
    
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
        
        $meta = $this->parseMetaTags($html);
        if (!$meta) {
            return null;
        }

        $features = $this->parseFeatures($html);
        $collection = $this->parseCollection($html);
        $msrp = $this->parseMsrp($html);

        $title = $meta["name"] ?? "";
        $descripcion = $meta["description"] ?? "";
        $imageUrl = $meta["image"] ?? "";
        $genero = $this->detectGender($title, $descripcion);

        $imagePath = $this->downloadImage($modelo, $imageUrl);

        $movimientoRaw = $features["movimiento"] ?? null;
        $color = $this->detectColor($title, $descripcion, $features["brazalete"] ?? null, $this->parseSpecColors($html));

        return [
            "title" => $title,
            "descripcion" => $descripcion,
            "imagen_url" => $imageUrl,
            "imagen_local" => $imagePath,
            "coleccion" => $collection,
            "genero" => $genero,
            "color" => $color,
            "msrp" => $msrp,
            "size" => $features["size"] ?? null,
            "caja" => $features["caja"] ?? null,
            "brazalete" => $features["brazalete"] ?? null,
            "tipo_movimiento" => Product::normalizeMovimiento($movimientoRaw),
            "movimiento_raw" => $movimientoRaw,
            "resistencia_agua" => $features["resistencia_agua"] ?? null,
        ];
    }

    private function parseMetaTags(string $html): ?array
    {
        $meta = [];
        if (preg_match('/<meta[^>]*itemprop="name"[^>]*content="([^"]*)"/i', $html, $m)) {
            $meta["name"] = trim($m[1]);
        }
        if (preg_match('/<meta[^>]*itemprop="description"[^>]*content="([^"]*)"/i', $html, $m)) {
            $meta["description"] = trim($m[1]);
        }
        if (preg_match('/<meta[^>]*itemprop="image"[^>]*content="([^"]*)"/i', $html, $m)) {
            $meta["image"] = trim($m[1]);
        }
        return !empty($meta) ? $meta : null;
    }

    private function parseFeatures(string $html): array
    {
        $features = [];

        if (preg_match('/<div[^>]*class="feature"[^>]*>.*?<div[^>]*class="feature-name"[^>]*>\s*Case\s*<.*?<div[^>]*class="feature-value"[^>]*>(.*?)<\/div>/is', $html, $m)) {
            $caseText = html_entity_decode(strip_tags($m[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $caseText = preg_replace('/[\s\xC2\xA0]+/u', ' ', $caseText);
            $caseText = trim($caseText);
            if (preg_match('/([\d.]+)\s*mm/i', $caseText, $sm)) {
                $features["size"] = trim($sm[1]);
            }
            $caseMaterial = preg_replace('/[\d.]+mm\s*,?\s*/i', '', $caseText);
            $features["caja"] = trim($caseMaterial);
        }

        if (preg_match('/<div[^>]*class="feature"[^>]*>.*?<div[^>]*class="feature-name"[^>]*>\s*Band\s*<.*?<div[^>]*class="feature-value"[^>]*>(.*?)<\/div>/is', $html, $m)) {
            $features["brazalete"] = trim(html_entity_decode(strip_tags($m[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        }

        if (preg_match('/<div[^>]*class="feature"[^>]*>.*?<div[^>]*class="feature-name"[^>]*>\s*Movement\s*<.*?<div[^>]*class="feature-value"[^>]*>(.*?)<\/div>/is', $html, $m)) {
            $features["movimiento"] = trim(html_entity_decode(strip_tags($m[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        }

        if (preg_match('/<div[^>]*class="feature"[^>]*>.*?<div[^>]*class="feature-name"[^>]*>\s*Water resistance\s*<.*?<div[^>]*class="feature-value[^"]*"[^>]*>(.*?)<\/div>/is', $html, $m)) {
            $features["resistencia_agua"] = trim(html_entity_decode(strip_tags($m[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        }

        return $features;
    }

    private function parseCollection(string $html): ?string
    {
        if (preg_match('/<div[^>]*class="crumbs[^"]*"[^>]*>.*?<a[^>]*>Home<.*?<a[^>]*>[^<]*<.*?<a[^>]*href="https:\/\/www\.invictawatch\.com\/watches\/[^"]*"[^>]*>([^<]+)<\/a>/is', $html, $m)) {
            return trim($m[1]);
        }
        return null;
    }

    private function parseMsrp(string $html): ?int
    {
        $html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        if (preg_match('/MSRP\s*\$?\s*([\d,]+)/i', $html, $m)) {
            return (int) str_replace(",", "", $m[1]);
        }
        return null;
    }

    private function parseSpecColors(string $html): array
    {
        $colors = [];
        if (preg_match_all('/<div class="spec-row">\s*<div class="spec-name"[^>]*>\s*([^<]+?)\s*<\/div>\s*<div class="spec-values"[^>]*>(.*?)<\/div>\s*<\/div>/is', $html, $rows, PREG_SET_ORDER)) {
            foreach ($rows as $row) {
                $section = trim($row[1]);
                if (!preg_match_all('/<span>\s*([^:<]+?)\s*:\s*([^<]+?)<\/span>/', $row[2], $pairs, PREG_SET_ORDER)) {
                    continue;
                }
                foreach ($pairs as $pair) {
                    $key = trim($pair[1]);
                    $value = trim($pair[2]);
                    if (mb_strtolower($key) === 'tone' && mb_strtolower($section) === 'band') {
                        $colors[] = ["key" => "Band Tone", "value" => $value];
                    } elseif (preg_match('/color$/i', $key)) {
                        $colors[] = ["key" => $key, "value" => $value];
                    }
                }
            }
        }
        return $colors;
    }

    private function detectColor(string $title, string $description, ?string $bandFeature, array $specColors): ?string
    {
        // El campo color = color del brazalete: prioriza el "Tone" de la sección Band.
        foreach ($specColors as $spec) {
            if ($spec["key"] === "Band Tone") {
                $match = $this->matchColor($spec["value"]);
                if ($match !== null) {
                    return $match;
                }
            }
        }

        // Fallback: material del brazalete (feature "Band").
        if ($bandFeature !== null && trim($bandFeature) !== '') {
            $match = $this->matchColor($bandFeature);
            if ($match !== null) {
                return $match;
            }
        }

        // Fallback: colores de specs (Bezel/Dial).
        $priority = ["Bezel Color", "Case Color", "Dial Color", "Band Color"];
        usort($specColors, function ($a, $b) use ($priority) {
            $pa = array_search($a["key"], $priority);
            $pb = array_search($b["key"], $priority);
            return ($pa === false ? 999 : $pa) <=> ($pb === false ? 999 : $pb);
        });
        foreach ($specColors as $spec) {
            $match = $this->matchColor($spec["value"]);
            if ($match !== null) {
                return $match;
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

    private function detectGender(string $title, string $description): string
    {
        $text = $title . " " . $description;
        if (preg_match('/\bWomen\b/i', $text)) {
            return "mujer";
        }
        if (preg_match('/\bMen\b/i', $text)) {
            return "hombre";
        }
        return "unisex";
    }

    private function downloadImage(string $modelo, string $imageUrl): ?string
    {
        $urlsToTry = [];

        $mainImage = self::CDN_URL . "/{$modelo}/{$modelo}_1.jpg";
        $urlsToTry[] = $mainImage;

        if (!empty($imageUrl) && $imageUrl !== $mainImage) {
            $urlsToTry[] = $imageUrl;
        }

        foreach ($urlsToTry as $url) {
            try {
                $response = Http::withHeaders(self::HEADERS)
                    ->timeout(30)
                    ->get($url);

                if (!$response->ok()) {
                    continue;
                }

                $ext = "jpg";
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
}
