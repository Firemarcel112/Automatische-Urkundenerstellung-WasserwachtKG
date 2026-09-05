<?php

namespace App\Providers;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public string $version = "1.0.0";

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        AboutCommand::add('Projektspezifische Informationen', [
            'Version' => $this->version,
        ]);
    }
}
