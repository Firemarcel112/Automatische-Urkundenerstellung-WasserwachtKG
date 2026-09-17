<?php

namespace Tests\Feature\Commands;

use Tests\TestCase;

class InstallTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testCommandRunsSuccessfullyWithMissingEnv(): void
    {
        $env_path = base_path('.env');
        $env_example_path = base_path('.env.example');

        if (!file_exists($env_example_path)) {
            $this->markTestIncomplete('.env.example fehlt');
        }

        $backup_env = file_exists($env_path) ? file_get_contents($env_path) : null;

        if (file_exists($env_path)) {
            @unlink($env_path);
        }

        try {
            $this->artisan('app:install')
                ->expectsOutput(' Erstelle .env Datei')
                ->assertExitCode(0);

            $this->assertFileExists($env_path);
        } finally {
            if (!is_null($backup_env)) {
                file_put_contents($env_path, $backup_env);
            }
        }
    }

    public function testCommandRunsSuccessfullyWithMissingDatabase(): void
    {
        $database_path = database_path('database.sqlite');
        $database_orig_path = database_path('database.original.sqlite');


        if (file_exists($database_path)) {
            copy($database_path, $database_orig_path);
            @unlink($database_path);
        }

        try {
            $this->artisan('app:install')
                ->expectsOutput(' database.sqlite erstellen')
                ->assertExitCode(0);

            $this->assertFileExists($database_path);
            @unlink($database_path);
        } finally {
            copy($database_orig_path, $database_path);
            if (file_exists($database_orig_path)) {
                @unlink($database_orig_path);
            }
        }
    }

    public function testCommandRunsSuccessfullyWithSetAppEncryptionKey(): void
    {
        $env_path = base_path('.env');

        $backup_env = file_exists($env_path) ? file_get_contents($env_path) : null;

        file_put_contents(
            $env_path,
            str_replace(
                'APP_KEY=' . config('app.key'),
                'APP_KEY=',
                file_get_contents($env_path)
            )
        );

        config(['app.key' => null]);

        try {
            $this->artisan('app:install')
                ->expectsOutput(' Encryption Key setzten')
                ->assertExitCode(0);

            $this->assertNotEmpty(config('app.key'));
        } finally {
            if (!is_null($backup_env)) {
                file_put_contents($env_path, $backup_env);
            }
        }
    }
}
