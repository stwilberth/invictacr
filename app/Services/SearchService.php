<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Http;

class SearchService
{
    private array $knownGenders = [];

    private array $knownCollections = [];

    private array $knownColors = [];

    private array $knownBrazaletes = [];

    private array $knownMovimientos = [];

    private array $knownCajas = [];

    private array $knownResistencias = [];

    private array $stopWords = [
        'para', 'con', 'de', 'en', 'por', 'y', 'a', 'e', 'o', 'del',
        'la', 'el', 'los', 'las', 'un', 'una', 'que', 'es', 'se', 'no',
        'su', 'lo', 'como', 'más', 'pero', 'sus', 'le', 'ya', 'este',
        'entre', 'porque', 'esa', 'eso', 'sin', 'desde', 'hasta',
    ];

    private array $genericWords = [
        'reloj', 'relojes', 'invicta', 'buscar', 'quiero', 'necesito',
        'comprar', 'ver', 'muestra', 'tipo', 'modelo', 'color', 'colores',
        'correa', 'correas', 'pulso', 'faja', 'fajas', 'pulsera', 'pulseras',
        'marca', 'original', 'originales', 'estilo', 'diseño',
    ];

    private array $synonyms = [
        // Movimientos
        "sin bateria" => ["tipo_movimiento" => "automatico"],
        "sin pila" => ["tipo_movimiento" => "automatico"],
        "bateria" => ["tipo_movimiento" => "cuarzo"],
        "pila" => ["tipo_movimiento" => "cuarzo"],
        "pilas" => ["tipo_movimiento" => "cuarzo"],
        "cuerda" => ["tipo_movimiento" => "automatico"],
        "maquinaria" => ["tipo_movimiento" => "automatico"],

        // Géneros
        "caballeros" => ["gender" => "hombre"],
        "caballero" => ["gender" => "hombre"],
        "varones" => ["gender" => "hombre"],
        "varon" => ["gender" => "hombre"],
        "hombres" => ["gender" => "hombre"],
        "chicos" => ["gender" => "hombre"],
        "chico" => ["gender" => "hombre"],
        
        "mujeres" => ["gender" => "mujer"],
        "damas" => ["gender" => "mujer"],
        "dama" => ["gender" => "mujer"],
        "chicas" => ["gender" => "mujer"],
        "chica" => ["gender" => "mujer"],
        "senoras" => ["gender" => "mujer"],
        "senora" => ["gender" => "mujer"],

        // Colores
        "oro rosa" => ["color" => "Oro Rosa"],
        "rosado" => ["color" => "Oro Rosa"],
        "rosa" => ["color" => "Oro Rosa"],
        "amarillo" => ["color" => "Dorado"],
        "amarillos" => ["color" => "Dorado"],
        "oro" => ["color" => "Dorado"],
        "plateada" => ["color" => "Plateado"],
        "plateadas" => ["color" => "Plateado"],
        "plateados" => ["color" => "Plateado"],
        "plata" => ["color" => "Plateado"],
        "acero" => ["color" => "Plateado"],
        "bicolor" => ["color" => "Plateado Dorado"],
        "combinado" => ["color" => "Plateado Dorado"],
        "negra" => ["color" => "Negro"],
        "negras" => ["color" => "Negro"],
        "negros" => ["color" => "Negro"],
        "gris" => ["color" => "Gris Oscuro"],
        "oscuro" => ["color" => "Gris Oscuro"],

        // Colecciones
        "carros" => ["coleccion" => "Speedway"],
        "carreras" => ["coleccion" => "Speedway"],
        "auto" => ["coleccion" => "Speedway"],
        "coches" => ["coleccion" => "Speedway"],
        "racing" => ["coleccion" => "Speedway"],
        "mickey" => ["coleccion" => "Disney"],
        "raton" => ["coleccion" => "Disney"],
        "piloto" => ["coleccion" => "Aviator"],
        "avion" => ["coleccion" => "Aviator"],
        "vuelo" => ["coleccion" => "Aviator"],
        "buzo" => ["coleccion" => "pro diver"],
        "buceo" => ["coleccion" => "pro diver"],
        "agua" => ["coleccion" => "pro diver"],
        "sumergible" => ["coleccion" => "pro diver"],
    ];

    public bool $usedAi = false;

    public ?string $aiResponse = null;

    public ?string $aiRawResponse = null;

    public ?string $aiSkippedReason = null;

    public function __construct()
    {
        $this->loadKnownValues();
    }

    private function loadKnownValues(): void
    {
        $base = Product::where('activo', true)->where('stock', '>', 0);

        $this->knownGenders = $base->clone()
            ->whereNotNull('genero')
            ->distinct()->pluck('genero')->map(fn ($v) => mb_strtolower($v))->values()->toArray();

        $this->knownColors = $base->clone()
            ->whereNotNull('color')
            ->distinct()->pluck('color')->map(fn ($v) => mb_strtolower($v))->values()->toArray();
        $this->knownColors = array_values(array_filter($this->knownColors, fn ($v) => $v !== 'otros'));

        $this->knownCollections = $base->clone()
            ->whereNotNull('coleccion')
            ->distinct()->pluck('coleccion')->map(fn ($v) => mb_strtolower($v))->values()->toArray();

        $this->knownBrazaletes = array_map('mb_strtolower', config('brazaletes', []));

        $this->knownMovimientos = ['cuarzo', 'automatico'];

        $this->knownCajas = ['acero inoxidable'];

        $allResistencias = $base->clone()
            ->whereNotNull('resistencia_agua')
            ->distinct()->pluck('resistencia_agua')->map(fn ($v) => (int) preg_replace('/[^0-9]/', '', $v))->toArray();
        $this->knownResistencias = array_values(array_unique(array_filter($allResistencias)));
        sort($this->knownResistencias);
    }

    public function parseWithDeepSeek(string $query): array
    {
        return $this->parseWithClaude($query);
    }

    public function parseWithClaude(string $query): array
    {
        $this->usedAi = false;
        $this->aiResponse = null;
        $this->aiRawResponse = null;
        $this->aiSkippedReason = null;

        if (! config('services.anthropic.key')) {
            $this->aiSkippedReason = 'no_api_key';

            return [];
        }

        // Si la consulta es mayormente un numero de modelo (4+ digitos),
        // NO invocamos la IA: no tiene sentido que "invente" una coleccion
        // para un modelo que probablemente no existe en el catalogo.
        if ($this->isModelNumberQuery($query)) {
            $this->aiSkippedReason = 'model_number_query';

            return [];
        }

        $result = $this->callClaude($query);
        $this->usedAi = true;
        $this->aiResponse = $result['response'];
        $this->aiRawResponse = $result['raw'];

        $filters = $result['filters'];

        // Validar los filtros devueltos por la IA contra productos reales.
        // Si la IA devuelve un filtro que no produce ningun producto, lo
        // descartamos en lugar de mostrar 0 resultados enganosos.
        $filters = $this->validateAiFilters($filters);

        return $filters;
    }

    /**
     * Detecta si una consulta es mayormente una busqueda por numero de modelo.
     * Ej: "69037", "IN-46086", "Pro Diver 26973" (numero significativo).
     */
    private function isModelNumberQuery(string $query): bool
    {
        // Extraer todos los digitos consecutivos de 4+ caracteres
        if (preg_match_all('/\d{4,}/', $query, $m)) {
            return true;
        }

        // Patrones tipo "IN-504", "in-46086" (prefijo + numero)
        $normalized = mb_strtolower(trim($query));
        if (preg_match('/^(?:in|inv|invicta)?[-\s]?\d{3,}/i', $normalized)) {
            return true;
        }

        return false;
    }

    /**
     * Valida que los filtros devueltos por la IA realmente produzcan
     * productos. Descarta los filtros que no coincidan con nada.
     */
    private function validateAiFilters(array $filters): array
    {
        if (empty($filters)) {
            return [];
        }

        $filterFields = array_filter($filters, fn ($k) => $k !== 'q', ARRAY_FILTER_USE_KEY);

        if (empty($filterFields)) {
            return $filters;
        }

        $query = Product::where('activo', true)->where('stock', '>', 0);

        foreach ($filterFields as $field => $value) {
            switch ($field) {
                case 'gender':
                    $query->whereRaw('LOWER(genero) = ?', [mb_strtolower($value)]);
                    break;
                case 'color':
                    $query->whereRaw('LOWER(color) = ?', [mb_strtolower($value)]);
                    break;
                case 'coleccion':
                    $query->whereRaw('LOWER(coleccion) = ?', [mb_strtolower($value)]);
                    break;
                case 'brazalete':
                    $query->whereRaw('LOWER(brazalete) = ?', [mb_strtolower($value)]);
                    break;
                case 'tipo_movimiento':
                    $query->whereRaw('LOWER(tipo_movimiento) = ?', [mb_strtolower($value)]);
                    break;
                case 'caja':
                    $query->whereRaw('LOWER(caja) = ?', [mb_strtolower($value)]);
                    break;
                case 'resistencia_agua':
                    $query->whereRaw('CAST(resistencia_agua AS UNSIGNED) = ?', [(int) $value]);
                    break;
            }
        }

        $count = $query->count();

        if ($count === 0) {
            // Los filtros de la IA no producen nada: descartarlos todos
            // y quedarnos solo con 'q' si lo habia, para que el controlador
            // haga una busqueda de texto libre como fallback.
            if (isset($filters['q']) && $filters['q'] !== '') {
                return ['q' => $filters['q']];
            }

            return [];
        }

        return $filters;
    }

    public function parse(string $query): array
    {
        $filters = [
            'q' => '',
            'gender' => '',
            'color' => '',
            'coleccion' => '',
            'brazalete' => '',
            'tipo_movimiento' => '',
            'caja' => '',
            'resistencia_agua' => '',
        ];

        $words = preg_split('/\s+/', trim($query));
        $used = [];
        $unmatched = [];

        $allPhrases = $this->buildPhrases($words);

        foreach ($allPhrases as $phrase) {
            $lower = mb_strtolower($phrase);
            if ($this->filterAlreadySet($phrase, $filters)) {
                continue;
            }
            $matched = false;

            if ($match = $this->matchField($lower, $this->knownGenders)) {
                $filters['gender'] = $match;
                $matched = true;
            }
            if (! $matched && ($match = $this->matchField($lower, $this->knownCollections))) {
                $filters['coleccion'] = $match;
                $matched = true;
            }
            if (! $matched && ($match = $this->matchField($lower, $this->knownColors))) {
                $filters['color'] = $match;
                $matched = true;
            }
            if (! $matched && ($match = $this->matchField($lower, $this->knownBrazaletes))) {
                $filters['brazalete'] = $match;
                $matched = true;
            }
            if (! $matched && ($match = $this->matchField($lower, $this->knownMovimientos))) {
                $filters['tipo_movimiento'] = $match;
                $matched = true;
            }
            if (! $matched && ($match = $this->matchField($lower, $this->knownCajas))) {
                $filters['caja'] = $match;
                $matched = true;
            }
            if (! $matched && $this->matchResistencia($lower, $match)) {
                $filters['resistencia_agua'] = $match;
                $matched = true;
            }

            if ($matched) {
                $used[$phrase] = true;
                $phraseWords = preg_split('/\s+/', $phrase);
                foreach ($phraseWords as $pw) {
                    $used[$pw] = true;
                }
            }
        }

        foreach ($words as $word) {
            if (! $this->wordInUsedPhrases($word, $used)) {
                $unmatched[] = $word;
            }
        }

        // Match synonyms on raw unmatched words before stripping stop/generic words
        $queryLower = mb_strtolower(implode(' ', $unmatched));
        foreach ($this->synonyms as $synonym => $mappedFilter) {
            if ($queryLower === $synonym || str_contains($queryLower, $synonym)) {
                foreach ($mappedFilter as $field => $value) {
                    if (empty($filters[$field])) {
                        $filters[$field] = $value;
                    }
                }
                $synonymWords = preg_split('/\s+/', $synonym);
                $unmatched = array_values(array_filter($unmatched, fn ($w) => !in_array(mb_strtolower($w), $synonymWords, true)));
                $queryLower = mb_strtolower(implode(' ', $unmatched));
            }
        }

        // Now strip stop words and generic words
        $unmatched = array_values(array_filter($unmatched, fn ($w) => ! in_array(mb_strtolower($w), $this->stopWords, true)));
        $unmatched = array_values(array_filter($unmatched, fn ($w) => ! in_array(mb_strtolower($w), $this->genericWords, true)));

        if (! empty($unmatched)) {
            $filters['q'] = implode(' ', $unmatched);
        }

        return array_filter($filters, fn ($v) => $v !== '');
    }

    private function matchResistencia(string $word, ?string &$match): bool
    {
        // Si tiene 4 o más dígitos, asumimos que es un número de modelo (ej: 0071, 1270)
        if (preg_match('/^\d{4,}$/', $word)) {
            return false;
        }

        // Extraer los dígitos
        if (! preg_match('/(\d+)/', $word, $m)) {
            return false;
        }
        $num = (int) $m[1];
        if ($num === 0) {
            return false;
        }

        // Comprobar si hay sufijos de unidad de resistencia al agua
        $hasSuffix = preg_match('/(?:m|mt|mts|meter|meters|metro|metros|bar|atm|wr)\b/i', $word);

        // Si el número coincide exactamente con una resistencia conocida, lo aceptamos
        if (in_array($num, $this->knownResistencias, true)) {
            $match = (string) $num;

            return true;
        }

        // Si no coincide exactamente, solo lo aproximamos si viene acompañado de un sufijo
        if ($hasSuffix) {
            $closest = null;
            $minDiff = PHP_INT_MAX;
            foreach ($this->knownResistencias as $val) {
                $diff = abs($val - $num);
                if ($diff < $minDiff) {
                    $minDiff = $diff;
                    $closest = $val;
                }
            }

            if ($closest !== null && $minDiff <= 50) {
                $match = (string) $closest;

                return true;
            }
        }

        return false;
    }

    private function buildPhrases(array $words): array
    {
        $phrases = [];
        $n = count($words);
        for ($i = 0; $i < $n; $i++) {
            for ($len = 1; $len <= min(3, $n - $i); $len++) {
                $phrase = implode(' ', array_slice($words, $i, $len));
                if (! in_array($phrase, $phrases)) {
                    $phrases[] = $phrase;
                }
            }
        }
        usort($phrases, fn ($a, $b) => str_word_count($b) - str_word_count($a));

        return $phrases;
    }

    private function matchField(string $word, array $knownValues): ?string
    {
        foreach ($knownValues as $value) {
            if ($value === $word || $this->normalize($value) === $this->normalize($word)) {
                return $value;
            }
        }
        foreach ($knownValues as $value) {
            similar_text($word, $value, $perc);
            if ($perc > 80) {
                return $value;
            }
        }

        return null;
    }

    private function filterAlreadySet(string $phrase, array $filters): bool
    {
        foreach ($filters as $key => $val) {
            if ($key !== 'q' && $val !== '' && $val !== null) {
                $wordsInVal = preg_split('/\s+/', mb_strtolower(trim($val)));
                $phraseWords = preg_split('/\s+/', mb_strtolower(trim($phrase)));
                if (! empty(array_intersect($wordsInVal, $phraseWords))) {
                    return true;
                }
            }
        }

        return false;
    }

    private function wordInUsedPhrases(string $word, array $used): bool
    {
        foreach ($used as $phrase => $_) {
            $phraseWords = preg_split('/\s+/', mb_strtolower(trim($phrase)));
            if (in_array(mb_strtolower($word), $phraseWords)) {
                return true;
            }
        }

        return false;
    }

    private function normalize(string $s): string
    {
        $s = mb_strtolower($s);
        $s = str_replace(['á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ'], ['a', 'e', 'i', 'o', 'u', 'u', 'n'], $s);

        return $s;
    }

    private function parseWithAI(string $query, bool $fallback = false): array
    {
        return $this->callClaude($query);
    }

    private function callClaude(string $query): array
    {
        $apiKey = config('services.anthropic.key');
        if (! $apiKey) {
            return ['filters' => [], 'response' => null, 'raw' => null];
        }

        $model = config('services.anthropic.model', 'claude-sonnet-4-6');
        $timeout = (int) config('services.anthropic.timeout', 15);

        $fields = [
            'genero' => $this->knownGenders,
            'color' => $this->knownColors,
            'coleccion' => $this->knownCollections,
            'brazalete' => $this->knownBrazaletes,
            'tipo_movimiento' => $this->knownMovimientos,
            'caja' => $this->knownCajas,
            'resistencia_agua' => $this->knownResistencias,
        ];

        $systemPrompt = "Eres un buscador de relojes Invicta. Recibes una consulta de búsqueda y debes extraer los campos de filtro que puedas identificar.

REGLAS:
1. IGNORA palabras genéricas: reloj, relojes, invicta, buscar, quiero, para, con, de, un, una, comprar, ver.
2. CORRIGE errores ortográficos (ej: 'mujjer' → 'Mujer', 'hombr' → 'Hombre', 'acero' → 'Acero Inoxidable').
3. MAPEA términos semánticamente similares al valor aceptado más cercano (ej: 'amarillo' → 'Dorado', 'carros' o 'coche' → busca colecciones como Speedway, Invicta Racing, S1 Rally, etc.).
4. Si un término no coincide exactamente con ningún valor aceptado, puedes devolverlo en el campo 'q' (texto de búsqueda libre).
5. Los valores de filtro deben coincidir EXACTAMENTE (respetando mayúsculas) con la lista de valores aceptados.
6. Piensa en términos relacionados: si alguien busca 'carros' probablemente busca colecciones de carreras (Speedway, Invicta Racing, S1 Rally).
7. Si la consulta es un NUMERO de modelo (4+ digitos), NO inventes una coleccion. Devuelve el numero en 'q' o {} si no hay nada.
8. NUNCA inventes un valor que no este en la lista de valores aceptados.

Valores aceptados por campo:
".json_encode($fields, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)."

Devuelve SOLO un JSON con los campos que identificaste. Incluye 'q' solo si hay términos de búsqueda libre que no correspondan a ningún filtro. Si no puedes determinar ningún campo, devuelve {}.
Ejemplos:
- {\"color\":\"Dorado\"} para 'amarillo'
- {\"coleccion\":\"Speedway\",\"q\":\"carreras\"} para 'carros speedway'
- {\"color\":\"Rojo\"} para 'rojo'
- {} para '69037'
";

        try {
            $response = Http::timeout($timeout)->withHeaders([
                'x-api-key' => $apiKey,
                'anthropic-version' => '2023-06-01',
                'Content-Type' => 'application/json',
            ])->post('https://api.anthropic.com/v1/messages', [
                'model' => $model,
                'max_tokens' => 300,
                'temperature' => 0,
                'system' => $systemPrompt,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $query,
                    ],
                ],
            ]);

            $data = $response->json();
            $rawContent = $data['content'][0]['text'] ?? '';
            $content = trim($rawContent);
            $content = preg_replace('/^```(?:json)?\s*|\s*```$/', '', $content);

            $parsed = json_decode($content, true);
            if (! is_array($parsed)) {
                return ['filters' => [], 'response' => $content, 'raw' => $rawContent];
            }

            $result = [];
            $fieldMap = [
                'genero' => 'gender',
                'color' => 'color',
                'coleccion' => 'coleccion',
                'brazalete' => 'brazalete',
                'tipo_movimiento' => 'tipo_movimiento',
                'caja' => 'caja',
                'resistencia_agua' => 'resistencia_agua',
                'q' => 'q',
            ];

            foreach ($fieldMap as $aiKey => $filterKey) {
                if (isset($parsed[$aiKey]) && ! empty($parsed[$aiKey])) {
                    $val = $parsed[$aiKey];
                    if (is_string($val)) {
                        $result[$filterKey] = $val;
                    }
                }
            }

            return ['filters' => $result, 'response' => $content, 'raw' => $rawContent];
        } catch (\Exception $e) {
            return ['filters' => [], 'response' => null, 'raw' => null];
        }
    }
}
