<?php

use \Illuminate\Support\Facades\Route;

Route::get('/create-order', function () {
    return 'Create order';
})->name('order.create');

Route::get('/orders', function () {
    return 'Orders';
})->name('order.index');
