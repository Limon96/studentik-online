<?php

use Illuminate\Support\Facades\Route;


Route::prefix('_manager')->group(function() {

    Route::get('/', function () {
        return redirect()
            ->route('login');
    })->name('admin.home');

    Auth::routes();

    Route::group(['middleware' => ['role:admin|moder']], function () {

        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

        Route::name('admin.')->group(function() {
            Route::resources([
                'blog_category' => \App\Http\Controllers\Blog\Admin\BlogCategoryController::class,
                'blog' => \App\Http\Controllers\Blog\Admin\BlogController::class,
                'user' => \App\Http\Controllers\Admin\UserController::class,
            ]);
        });
    });


    Route::group(['middleware' => ['role:admin']], function () {
        Route::resource('landing', \App\Http\Controllers\Landing\Admin\LandingController::class)->names('admin.landing');
        Route::get('landing/{landing}/copy', [\App\Http\Controllers\Landing\Admin\LandingController::class, 'copy'])->name('admin.landing.copy');

        Route::get('pagebuilder/{block}', [\App\Http\Controllers\PageBuilder\PageBuilderController::class, 'index'])->name('admin.pagebuilder.index');

        Route::name('admin.')->group(function() {
            Route::resources([
                'faq' => \App\Http\Controllers\FAQ\Admin\FAQController::class,
                'faq_category' => \App\Http\Controllers\FAQ\Admin\FAQCategoryController::class,
                'work_type' => \App\Http\Controllers\Order\Admin\WorkTypeController::class,
            ]);
            Route::resource('withdrawal', \App\Http\Controllers\Withdrawal\Admin\WithdrawalController::class)->only(['index', 'destroy']);
            Route::get('withdrawal/{withdrawal}/confirm', [\App\Http\Controllers\Withdrawal\Admin\WithdrawalController::class, 'confirm'])->name('withdrawal.confirm');
            Route::get('withdrawal/{withdrawal}/cancel', [\App\Http\Controllers\Withdrawal\Admin\WithdrawalController::class, 'cancel'])->name('withdrawal.cancel');

            Route::get('newsletter', [\App\Http\Controllers\Feedback\Admin\NewsletterController::class, 'index'])->name('newsletter.index');
            Route::post('newsletter', [\App\Http\Controllers\Feedback\Admin\NewsletterController::class, 'send'])->name('newsletter.send');
            Route::get('preview', [\App\Http\Controllers\Feedback\Admin\PreviewMailController::class, 'index'])->name('preview.index');
        });

        /* Settings */

        Route::get('/settings', [\App\Http\Controllers\Setting\Admin\SettingController::class, 'index'])->name('admin.setting');
        Route::post('/settings', [\App\Http\Controllers\Setting\Admin\SettingController::class, 'update']);
    });

});
