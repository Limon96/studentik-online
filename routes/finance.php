<?php

use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function () {

    Route::get('/finance', function () {
        return 'Finance';
    })->name('finance');

    Route::get('/finance/payment', function () {
        return 'Finance payment';
    })->name('finance.payment');

    Route::get('/finance/output', function () {
        return 'Finance output';
    })->name('finance.output');

});
