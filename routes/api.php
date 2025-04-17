<?php

use App\Http\Controllers\InvoicesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::resource('products', ProductsController::class);


Route::get('facturas', [InvoicesController::class, 'index']);
Route::get('facturas/{id}', [InvoicesController::class, 'show']);
Route::post('facturas/factura-global-por-valores', [InvoicesController::class, 'facturaGlobalPorValores']);
Route::post('facturas/factura-global-por-referencias', [InvoicesController::class, 'facturaGlobalPorReferencias']);
Route::post('facturas/factura-con-iva-16', [InvoicesController::class, 'facturaConIva16']);
Route::post('facturas/factura-iva-exento', [InvoicesController::class, 'facturaIvaExento']);
Route::post('facturas/factura-iva-tasa-cero', [InvoicesController::class, 'facturaIvaTasaCero']);
Route::post('facturas/factura-por-referencias', [InvoicesController::class, 'facturaPorReferencias']);




// Product Routes
// Route::prefix('products')->group(function () {
//     Route::get('/', [ProductsController::class, 'index']); // List all products
//     Route::get('/{id}', [ProductsController::class, 'show']); // Get a specific product
//     Route::post('/', [ProductsController::class, 'store']); // Create a new product
//     Route::put('/{id}', [ProductsController::class, 'update']); // Update a product
//     Route::delete('/{id}', [ProductsController::class, 'destroy']); // Delete a product
// });


