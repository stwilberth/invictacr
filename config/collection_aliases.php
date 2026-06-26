<?php

/**
 * Alias de colecciones: valores existentes en la DB que no
 * coinciden (lowercase) con ningún nombre canonical y deben
 * normalizarse a uno distinto.
 *
 * Formato: lowercase(alias) => 'Canonical'.
 * Usado por `invicta:normalize-collections`.
 */
return [
    'coalition' => 'Coalition Forces',
    'i-force' => 'Otros',
    'marvel' => 'Otros',
];
