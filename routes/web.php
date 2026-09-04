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

        Route::get('export/xlsx/{certificate}', [App\Http\Controllers\CertificateSettingsController::class, 'exportXlsx'])
            ->name('export.xlsx');

        Route::name('settings.')
            ->prefix('settings')
            ->controller(App\Http\Controllers\CertificateSettingsController::class)
            ->group(function () {
                Route::get('/{certificate}', 'show')->name('show');
                Route::get('/{certificate}/document', 'showDocument')->name('showDocument');
                Route::post('/{certificate}', 'update')->name('update');
                Route::delete('/{certificate}', 'destroy')->name('delete');
            });

        Route::name('create.')
            ->prefix('create')
            ->controller(App\Http\Controllers\CertificateCreateController::class)
            ->group(function () {
                Route::match(['GET', 'POST'], '/{certificate}', 'show')->name('show');
                Route::post('/store/{certificate}', 'store')->name('store');

                Route::post('import/xlsx/{certificate}', 'importXlsx')->name('import.xlsx');
            });
    });

Route::name('documents.')
    ->prefix('documents')
    ->group(function () {
        Route::controller(App\Http\Controllers\DocumentController::class)
            ->group(function () {
                Route::get('', 'index')->name('index');
                Route::get('show/{document}-{hash}', 'show')->name('show');
                Route::get('download/{document}-{hash}', 'download')->name('download');
            });
    });
