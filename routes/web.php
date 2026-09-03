<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::name('certificates.')
    ->prefix('certificates')->group(function () {

        Route::name('upload.')
            ->prefix('upload')
            ->controller(App\Http\Controllers\CertificateUploadController::class)
            ->group(function () {
                Route::post('/upload', 'store')->name('store');
            });
    });
