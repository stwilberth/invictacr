<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdImageController;
use App\Http\Controllers\Controller;
use App\Models\StoryHistory;

class StoryArtController extends Controller
{
    public function download(StoryHistory $history)
    {
        $product = $history->product;

        if (!$product) {
            abort(404, 'El producto de esta historia ya no existe.');
        }

        $png = (new AdImageController())->generateStory($product);
        $filename = 'historia-' . $history->channel . '-' . preg_replace('/[^a-z0-9-]+/i', '-', (string) $history->model_code) . '-' . $history->created_at->format('Ymd') . '.png';

        return response($png, 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
