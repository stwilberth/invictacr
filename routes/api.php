<?php

use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\StockApiController;
use App\Http\Controllers\Api\MarketingApiController;
use App\Http\Controllers\Api\MetaApiController;
use App\Http\Controllers\Api\SubscriberApiController;
use App\Http\Controllers\Api\UtilityApiController;
use App\Http\Controllers\Api\LiveSearchController;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ProductApiController::class, 'index']);
Route::get('/products/search', [ProductApiController::class, 'search']);
Route::get('/products/{modelo}', [ProductApiController::class, 'show']);
Route::post('/products/track-view', [ProductApiController::class, 'trackView']);

Route::post('/stock/sync', [StockApiController::class, 'sync']);

Route::post('/marketing/generate-description', [MarketingApiController::class, 'generateDescription']);
Route::post('/marketing/generate-ad', [MarketingApiController::class, 'generateAdContent']);

Route::get('/meta/catalog', [MetaApiController::class, 'catalog']);
Route::get('/meta/token', [MetaApiController::class, 'token']);
Route::post('/meta/token', [MetaApiController::class, 'storeToken']);

Route::get('/facebook-catalog.xml', [CatalogController::class, 'facebookCatalog']);
Route::get('/whatsapp-catalog.xml', [CatalogController::class, 'whatsappCatalog']);

Route::post('/subscribe', [SubscriberApiController::class, 'subscribe']);
Route::get('/subscribers', [SubscriberApiController::class, 'list']);

Route::get('/sitemap', [UtilityApiController::class, 'sitemap']);
Route::get('/sitemap.xml', [UtilityApiController::class, 'sitemap']);
Route::post('/cache/clear', [UtilityApiController::class, 'clearCache']);

Route::get('/live-search', LiveSearchController::class)->middleware('throttle:search');

// PayPal webhook (sin CSRF para que PayPal pueda enviar notificaciones)
Route::post('/paypal/webhook', [\App\Http\Controllers\PayPalController::class, 'webhook'])->name('paypal.webhook');
