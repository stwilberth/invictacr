<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    public function comoComprar()
    {
        return view('pages.como-comprar');
    }

    public function formasPago()
    {
        return view('pages.formas-pago');
    }

    public function envio()
    {
        return view('pages.envio');
    }

    public function garantia()
    {
        return view('pages.garantia');
    }

    public function resistenciaAgua()
    {
        return view('pages.resistencia-agua');
    }

    public function sobreNosotros()
    {
        return view('pages.sobre-nosotros');
    }

    public function privacidad()
    {
        return view('pages.privacidad');
    }

    public function redes()
    {
        return view('pages.redes');
    }

    public function resenas()
    {
        $videos = Cache::remember('resenas_r2_videos', 3600, function () {
            try {
                $files = Storage::disk('r2')->files('resennas');
            } catch (\Exception $e) {
                return [];
            }

            $mp4 = array_filter($files, fn($f) => str_ends_with(strtolower($f), '.mp4'));
            sort($mp4, SORT_NATURAL | SORT_FLAG_CASE);

            $cdnBase = 'https://cdn.invictacostarica.com';

            return array_map(function ($path) {
                $segments = explode('/', $path);
                $encoded = implode('/', array_map('rawurlencode', $segments));

                return [
                    'path' => $path,
                    'url' => "https://cdn.invictacostarica.com/{$encoded}",
                    'nombre' => pathinfo($path, PATHINFO_FILENAME),
                ];
            }, array_values($mp4));
        });

        return view('pages.resenas', compact('videos'));
    }
}
