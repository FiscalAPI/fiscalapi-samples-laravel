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
Route::post('facturas/nota-credito', [InvoicesController::class, 'notaCredito']);
Route::post('facturas/nota-credito-por-referencias', [InvoicesController::class, 'notaCreditoPorReferencias']);
Route::post('facturas/complemento-pago', [InvoicesController::class, 'complementoPago']);
Route::post('facturas/complemento-pago-por-referencias', [InvoicesController::class, 'complementoPagoPorReferencias']);
Route::post('facturas/complemento-pago-usd', [InvoicesController::class, 'complementoPagoUsd']);
Route::post('facturas/complemento-pago-mxn-usd', [InvoicesController::class, 'complementoPagoMxnUsd']);