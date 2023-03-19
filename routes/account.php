<?php

use Illuminate\Support\Facades\Route;


Route::get('/user/{login}', function ($login) {
    return "Profile {$login}";
})->name('account.profile');

Route::middleware('auth')->group(function () {

    Route::get('/setting', function () {
        return 'Setting';
    })->name('account.setting');

    Route::get('/password', function () {
        return 'Password';
    })->name('account.password');


    Route::get('/events', function () {
        return 'Events';
    })->name('account.event');

});
