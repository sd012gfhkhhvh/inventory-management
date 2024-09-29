<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return redirect()->route('items.index');
});

Route::resource('items', ItemController::class);
Route::post('/items/{item}/dispatch', [ItemController::class, 'dispatch'])->name('items.dispatch');
Route::post('/items/{item}/restock', [ItemController::class, 'restock'])->name('items.restock');
Route::get('/items/export', [ItemController::class, 'export'])->name('items.export');

Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/pdf', [ItemController::class, 'generatePdf'])->name('reports.pdf');

Route::get('/items', [ItemController::class, 'index'])->name('items.index');