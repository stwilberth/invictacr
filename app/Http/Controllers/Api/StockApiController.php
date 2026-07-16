<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\VariedadesSyncService;

class StockApiController extends Controller
{
    public function sync()
    {
        $service = app(VariedadesSyncService::class);
        $result = $service->execute();

        if ($result["success"]) {
            return response()->json([
                "success" => true,
                "created" => $result["created"],
                "stock_changed" => $result["stock_changed"],
                "reference_updated" => $result["reference_updated"],
                "marked_agotado" => $result["marked_agotado"],
                "message" => $result["message"],
                "details" => $result["details"],
            ]);
        }

        return response()->json(
            ["success" => false, "error" => $result["error"]],
            500,
        );
    }
}
