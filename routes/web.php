<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::prefix('new-order')->group(function () {

    include 'admin.php';

    Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/{slug}', [\App\Http\Controllers\Landing\LandingController::class, 'index'])->name('landing.show');

});


Route::prefix('blog')->group(function () {

    Route::get('/', [\App\Http\Controllers\Blog\BlogController::class, 'index'])->name('blog.index');
});
