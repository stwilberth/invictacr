<?php

/**
 * Alias de colecciones: valores que deben mapearse a uno canonical.
 *
 * Formato: 'alias' => 'Canonical'.
 * Usado por:
 *  - `invicta:normalize-collections` (sanea la DB).
 *  - `Product::normalizeColeccion()` (sanitiza al guardar).
 */
return [
    // Variants → canonical
    'Bolt Lady'        => 'Bolt',
    'Bolt Zeus'        => 'Bolt',
    'Bolt GOLD RUSH'   => 'Bolt',
    'Pro Diver Exclusive' => 'Pro Diver',
    'Pro Diver SCUBA'  => 'Pro Diver',
    'Angel Lady'       => 'Angel',
    'Specialty Lady'   => 'Specialty',
    'Grand Diver Miami Edition' => 'Grand Diver',
    'Ocean Predator Boy' => 'Ocean',
    'Ocean Voyage'     => 'Ocean',
    'Ocean PREDATOR Boy' => 'Ocean',
    'OCEAN VOYAGE'     => 'Ocean',
    'OCEAN PREDATOR Boy' => 'Ocean',
    'Coalition Forces' => 'Coalition',
    'Invicta Racing Saphirex' => 'Invicta Racing',
    'S1 Rally Interstellar' => 'S1 Rally',
    'S1 Rally Store Exclusive' => 'S1 Rally',
    'Ti-22'            => 'TI-22',
    'ti-22'            => 'TI-22',

    // Legacy / typo aliases
    'coalition'        => 'Coalition',
    'i-force'          => 'I-Force',
    'marvel'           => 'Marvel',
    'invicta racing saphirex' => 'Invicta Racing',
];
