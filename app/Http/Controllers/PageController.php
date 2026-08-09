<?php

namespace App\Http\Controllers;

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

    public function resenas()
    {
        $videos = \App\Models\ReviewVideo::activos()
            ->orderBy('orden')
            ->orderBy('id')
            ->get();

        return view('pages.resenas', compact('videos'));
    }

    public function sobreNosotros()
    {
        $reviewVideos = \App\Models\ReviewVideo::activos()
            ->orderBy('orden')
            ->orderBy('id')
            ->take(8)
            ->get();

        return view('pages.sobre-nosotros', compact('reviewVideos'));
    }

    public function privacidad()
    {
        return view('pages.privacidad');
    }
}
