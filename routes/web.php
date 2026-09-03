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

        Route::name('settings.')
            ->prefix('settings')
            ->controller(App\Http\Controllers\CertificateSettingsController::class)
            ->group(function () {
                Route::get('/{certificate}', 'show')->name('show');
                Route::get('/{certificate}/document', 'showDocument')->name('showDocument');
                Route::post('/{certificate}', 'update')->name('update');
                Route::delete('/{certificate}', 'destroy')->name('delete');
            });
    });
