<?php

namespace App\Http\Controllers;

use App\Models\ReviewVideo;

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
        $videos = ReviewVideo::activos()->orderBy('orden')->orderBy('id')->get();

        return view('pages.resenas', compact('videos'));
    }
}
