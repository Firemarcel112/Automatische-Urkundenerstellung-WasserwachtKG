<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

#[Signature('app:install')]
#[Description('Richtet alles ein für den Ersten Start')]
class Install extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line('Starte einrichten');
        Artisan::call('config:clear');

        $bar = $this->output->createProgressBar(4);

        if (!file_exists(base_path('.env'))) {
            $bar->advance();
            $this->line(' Erstelle .env Datei');
            copy(base_path('.env.example'), base_path('.env'));
        } else {
            $bar->advance();
            $this->line(' .env Existiert bereits');
        }

        sleep(1);

        if (empty(config('app.key'))) {
            $bar->advance();
            $this->line(" Encryption Key setzten");
            Artisan::call('key:generate');
        } else {
            $bar->advance();
            $this->line(' Encryption Key bereits gesetzt');
        }

        sleep(1);

        if (config('database.default') == 'sqlite' && !file_exists(database_path('database.sqlite'))) {
            $bar->advance();
            $this->line(" database.sqlite erstellen");
            touch(database_path('database.sqlite'));
        } else {
            $bar->advance();
            $this->line(" database.sqlite existiert bereits");
        }

        sleep(1);

        $bar->advance();
        $this->line(" richte Datenbank ein");
        Artisan::call('migrate', ['--force' => true], $this->output);

        $bar->finish();

        $this->line('Anwendung gestartet');
        // @codeCoverageIgnoreStart
        if (!app()->runningUnitTests()) {
            Artisan::call('serve', [],  $this->output);
        }
        // @codeCoverageIgnoreEnd
    }
}
