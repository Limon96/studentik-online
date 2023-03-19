<?php

use \Illuminate\Support\Facades\Route;

Route::get('/orders', \App\Http\Controllers\Order\ListController::class)->name('order.index');

Route::middleware('auth')->group(function () {
    Route::get('/create-order', [\App\Http\Controllers\Order\CreateController::class, 'showForm'])->name('order.create');
    Route::post('/create-order', [\App\Http\Controllers\Order\CreateController::class, 'create']);
});

Route::get('/order/to-verification/{order_id}', [\App\Http\Controllers\Order\OrderController::class, 'toVerification'])->name('order.to_verification');
Route::get('/order/{slug}', \App\Http\Controllers\Order\ShowController::class)->name('order.show');
Route::get('/edit/{slug}', fn (string $slug) => 'Edit order: ' . $slug)->name('order.edit');

Route::get('_autocomplete/subjects', \App\Http\Controllers\Autocomplete\SubjectController::class)->name('autocomplete.subjects');
