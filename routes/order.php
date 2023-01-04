<?php

use \Illuminate\Support\Facades\Route;

Route::get('/create-order', function () {
    return 'Create order';
})->name('order.create');

Route::get('/orders', \App\Http\Controllers\Order\ListController::class)->name('order.index');
Route::get('/order/{slug}', function ($slug){
    return 'Order show: ' . $slug;
})->name('order.show');
