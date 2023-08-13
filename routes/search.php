<?php

use \Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Search\IndexController;

Route::get('/search', IndexController::class)->name('search.index');
