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

include 'admin.php';

Route::prefix('new-order')->group(function () {
    Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/{slug}', [\App\Http\Controllers\Landing\LandingController::class, 'index'])->name('landing.show');
});


Route::prefix('blog')->group(function () {
    Route::get('/{slug?}', [\App\Http\Controllers\Blog\BlogController::class, 'index'])->name('blog.index');
    Route::get('/post/{slug}', [\App\Http\Controllers\Blog\BlogController::class, 'show'])->name('blog.show');

    Route::group(['middleware' => ['role:admin']], function () {
        Route::get('/preview/{slug}', [\App\Http\Controllers\Blog\BlogController::class, 'preview'])->name('blog.preview');
    });

});

Route::get('/faq', [\App\Http\Controllers\FAQ\FAQController::class, 'index'])->name('faq.index');

Route::get('/unsubscribe', function () {
    return '';
})->name('unsubscribe');

Route::get('/order/{slug}', function ($slug) {
    return $slug;
})->name('order.show');

Route::get('/user/{slug}', function ($slug) {
    return $slug;
})->name('user.show');

#Route::get('sign_in', [\App\Http\Controllers\Auth\SignInController::class, 'showLoginForm'])->name('sign_in');
#Route::post('sign_in', [\App\Http\Controllers\Auth\SignInController::class, 'login']);

