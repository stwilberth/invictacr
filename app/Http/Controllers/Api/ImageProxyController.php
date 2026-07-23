<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ImageProxyController extends Controller
{
    public function show(Request $request, $path)
    {
        $allowedDomains = [
            'cdn.invictacostarica.com',
            'invictacostarica.com',
        ];

        $url = 'https://cdn.invictacostarica.com/' . $path;

        $cacheKey = 'image_proxy_' . md5($url);

        return Cache::remember($cacheKey, 3600, function () use ($url) {
            try {
                $response = Http::timeout(10)->get($url);

                if (!$response->successful()) {
                    abort(404);
                }

                $contentType = $response->header('Content-Type') ?? 'image/webp';

                return response($response->body(), 200)
                    ->header('Content-Type', $contentType)
                    ->header('Access-Control-Allow-Origin', '*')
                    ->header('Cache-Control', 'public, max-age=3600');
            } catch (\Exception $e) {
                abort(404);
            }
        });
    }
}
