<?php

namespace App\Providers;

use Fiscalapi\Http\FiscalApiSettings;
use Fiscalapi\Services\FiscalApiClient;
use Illuminate\Support\ServiceProvider;

class FiscalApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/fiscalapi.php', 'fiscalapi'
        );

        $this->app->singleton(FiscalApiClient::class, function ($app) {
            $config = config('fiscalapi');

            $settings = new FiscalApiSettings(
                $config['apiUrl'],
                $config['apiKey'],
                $config['tenant'],
                $config['debug'] ?? false,
                $config['verifySsl'] ?? true,
                $config['apiVersion'] ?? 'v4',
                $config['timeZone'] ?? 'America/Mexico_City'
            );

            return new FiscalApiClient($settings);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/fiscalapi.php' => config_path('fiscalapi.php'),
        ], 'fiscalapi-config');
    }
}
