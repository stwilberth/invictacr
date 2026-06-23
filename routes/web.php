<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/relojes', [ProductController::class, 'index'])->name('products.index');
Route::get('/relojes/{gender}', [ProductController::class, 'byGender'])->name('products.gender');
Route::get('/relojes/{gender}/{slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/buscar', [SearchController::class, 'index'])->name('search');

Route::get('/como-comprar', [PageController::class, 'comoComprar'])->name('como-comprar');
Route::get('/formas-pago', [PageController::class, 'formasPago'])->name('formas-pago');
Route::get('/informacion-de-envio', [PageController::class, 'envio'])->name('envio');
Route::get('/metodos-envio', fn() => redirect('/informacion-de-envio', 301));
Route::get('/garantia', [PageController::class, 'garantia'])->name('garantia');
Route::get('/resistencia-agua', [PageController::class, 'resistenciaAgua'])->name('resistencia-agua');
Route::get('/resenas', [PageController::class, 'resenas'])->name('resenas');
Route::get('/sobre-nosotros', [PageController::class, 'sobreNosotros'])->name('sobre-nosotros');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/invoices/{invoice}/pdf', [InvoicePdfController::class, 'download'])->name('invoice.pdf');
    Route::get('/invoices/{invoice}/preview', [InvoicePdfController::class, 'preview'])->name('invoice.preview');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');
    Route::get('/products', \App\Livewire\Admin\Products::class)->name('products');
    Route::get('/products/create', \App\Livewire\Admin\ProductForm::class)->name('products.create');
    Route::get('/products/{productId}/edit', \App\Livewire\Admin\ProductForm::class)->name('products.edit');
    Route::get('/inventory', \App\Livewire\Admin\Inventory::class)->name('inventory');
    Route::get('/invoices', \App\Livewire\Admin\Invoices::class)->name('invoices');
    Route::get('/clients', \App\Livewire\Admin\Clients::class)->name('clients');
    Route::get('/expenses', \App\Livewire\Admin\Expenses::class)->name('expenses');
    Route::get('/marketing', \App\Livewire\Admin\Marketing::class)->name('marketing');
    Route::get('/campaigns', \App\Livewire\Admin\Campaigns::class)->name('campaigns');
    Route::get('/upcoming', \App\Livewire\Admin\Upcoming::class)->name('upcoming');
});

require __DIR__ . '/auth.php';
