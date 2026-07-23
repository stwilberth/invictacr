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
        return view('pages.resenas');
    }

    public function sobreNosotros()
    {
        return view('pages.sobre-nosotros');
    }

    public function privacidad()
    {
        return view('pages.privacidad');
    }
}
