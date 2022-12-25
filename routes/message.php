<?php

use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function () {

    Route::get('/messages', function () {
        return 'Messages';
    })->name('messages');

});
