<?php

use \Illuminate\Support\Facades\Route;

Route::get('/orders', \App\Http\Controllers\Order\ListController::class)->name('order.index');

Route::middleware('auth')->group(function () {
    Route::get('/create-order', [\App\Http\Controllers\Order\CreateController::class, 'showForm'])->name('order.create');
    Route::post('/create-order', [\App\Http\Controllers\Order\CreateController::class, 'create']);
});

Route::get('/order/{slug}', function ($slug){
    return 'Order show: ' . $slug;
})->name('order.show');

Route::get('_autocomplete/subjects', \App\Http\Controllers\Autocomplete\SubjectController::class)->name('autocomplete.subjects');
