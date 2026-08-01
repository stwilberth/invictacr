<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoicePdfController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\OgImageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/relojes', [ProductController::class, 'index'])->name('products.index')->middleware('throttle:search');
    Route::get('/relojes/{gender}', [ProductController::class, 'byGender'])->name('products.gender')->where('gender', 'hombre|mujer|unisex');
Route::get('/relojes/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::post('/relojes/{slug}/marcar-agotado', [ProductController::class, 'markAgotado'])->name('products.mark-agotado')->middleware(['auth', 'admin']);
Route::get('/relojes/{gender}/{slug}', function ($gender, $slug) {
    return redirect()->route('products.show', ['slug' => $slug], 301);
})->where('gender', 'hombre|mujer|unisex');



Route::get('/carrito', [\App\Http\Controllers\CarritoController::class, 'show'])->name('cart.show');
Route::post('/carrito', [\App\Http\Controllers\CarritoController::class, 'add'])->name('cart.add');
Route::patch('/carrito/{item}', [\App\Http\Controllers\CarritoController::class, 'update'])->name('cart.update');
Route::delete('/carrito/{item}', [\App\Http\Controllers\CarritoController::class, 'remove'])->name('cart.remove');
Route::delete('/carrito', [\App\Http\Controllers\CarritoController::class, 'clear'])->name('cart.clear');

Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/pedido/confirmado/{invoice}', [CheckoutController::class, 'confirmation'])->name('order.confirmation');

    Route::get('/paypal/create', [PayPalController::class, 'create'])->name('paypal.create');
    Route::get('/paypal/execute', [PayPalController::class, 'execute'])->name('paypal.execute');
    Route::get('/paypal/cancel', [PayPalController::class, 'cancel'])->name('paypal.cancel');
});

Route::get('/mis-pedidos', [OrderTrackingController::class, 'show'])->name('order-tracking.show');
Route::post('/mis-pedidos', [OrderTrackingController::class, 'search'])->name('order-tracking.search');

Route::get('/como-comprar', [PageController::class, 'comoComprar'])->name('como-comprar');
Route::get('/formas-pago', [PageController::class, 'formasPago'])->name('formas-pago');
Route::get('/informacion-de-envio', [PageController::class, 'envio'])->name('envio');
Route::get('/metodos-envio', fn() => redirect('/informacion-de-envio', 301));
Route::get('/garantia', [PageController::class, 'garantia'])->name('garantia');
Route::get('/resistencia-agua', [PageController::class, 'resistenciaAgua'])->name('resistencia-agua');
Route::get('/resenas', [PageController::class, 'resenas'])->name('resenas');
Route::get('/sobre-nosotros', [PageController::class, 'sobreNosotros'])->name('sobre-nosotros');
Route::get('/privacidad', [PageController::class, 'privacidad'])->name('privacidad');

Route::post('/subscribe', [\App\Http\Controllers\SubscriberController::class, 'subscribe'])->name('subscribe');

// Visitor tracking (sin CSRF: se envía por fetch/sendBeacon)
Route::post('/track/event', [\App\Http\Controllers\Api\VisitorTrackController::class, 'event'])->middleware('throttle:60,1')->name('track.event');
Route::post('/track/heartbeat', [\App\Http\Controllers\Api\VisitorTrackController::class, 'heartbeat'])->middleware('throttle:120,1')->name('track.heartbeat');
Route::get('/sitemap.xml', [\App\Http\Controllers\Api\UtilityApiController::class, 'sitemap']);
Route::get('/og/product/{slug}.png', [OgImageController::class, 'product'])->name('og.product');
Route::get('/sitemap', function () {
    return redirect('/sitemap.xml', 301);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/perfil', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('/perfil', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/invoices/{invoice}/pdf', [InvoicePdfController::class, 'download'])->name('invoice.pdf');
    Route::get('/invoices/{invoice}/preview', [InvoicePdfController::class, 'preview'])->name('invoice.preview');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');
    Route::get('/products', \App\Livewire\Admin\Products::class)->name('products');
    Route::get('/products/create', \App\Livewire\Admin\ProductForm::class)->name('products.create');
    Route::get('/products/{productId}/edit', \App\Livewire\Admin\ProductForm::class)->name('products.edit');
    Route::get('/invoices', \App\Livewire\Admin\Invoices::class)->name('invoices');
    Route::get('/invoices/create', \App\Livewire\Admin\InvoiceCreate::class)->name('invoices.create');
    Route::get('/invoices/{id}', \App\Livewire\Admin\InvoiceDetail::class)->name('invoices.detail');
    Route::get('/clients', \App\Livewire\Admin\Clients::class)->name('clients');
    Route::get('/users', \App\Livewire\Admin\Users::class)->name('users');
    Route::get('/subscribers', \App\Livewire\Admin\Subscribers::class)->name('subscribers');
    Route::get('/expenses', \App\Livewire\Admin\Expenses::class)->name('expenses');
    Route::get('/marketing', \App\Livewire\Admin\Marketing::class)->name('marketing');
    Route::get('/campaigns', \App\Livewire\Admin\Campaigns::class)->name('campaigns');
    Route::get('/upcoming', \App\Livewire\Admin\Upcoming::class)->name('upcoming');
    Route::get('/sync', \App\Livewire\Admin\SyncManager::class)->name('sync');
    Route::get('/search-logs', \App\Livewire\Admin\SearchLogs::class)->name('search-logs');
    Route::get('/optimize-images',  \App\Livewire\Admin\OptimizeImages::class)->name('optimize-images');
    Route::get('/db-backups',       \App\Livewire\Admin\DbBackups::class)->name('db-backups');
    Route::get('/analytics',        fn() => redirect()->route('admin.dashboard'))->name('analytics');
    Route::get('/visitors',         \App\Livewire\Admin\Visitors::class)->name('visitors');
    Route::get('/visitors/{id}',    \App\Livewire\Admin\VisitorDetail::class)->name('visitors.detail');
    Route::get('/timeline',         \App\Livewire\Admin\UnifiedTimeline::class)->name('timeline');
    Route::get('/github', \App\Livewire\Admin\GitHubReport::class)->name('github');
});

require __DIR__ . '/auth.php';
