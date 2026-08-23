<?php

declare(strict_types=1);

namespace The6FallenAngel\VarizaLaravel;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use The6FallenAngel\VarizaLaravel\Http\Controllers\WebhookController;
use The6FallenAngel\VarizaLaravel\Http\Middleware\VerifyVarizaWebhookSignature;

final class VarizaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/variza.php',
            'variza'
        );

        $this->app->singleton(VarizaClient::class, function ($app) {
            $config = $app['config']['variza'];

            return new VarizaClient(
                apiToken: $config['api_token'] ?? '',
                baseUrl: $config['base_url'] ?? 'https://variza.ir/api/v1',
                timeout: $config['timeout'] ?? 30,
            );
        });

        $this->app->alias(VarizaClient::class, 'variza');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/variza.php' => config_path('variza.php'),
            ], 'variza-config');
        }

        $this->registerRoutes();
    }

    protected function registerRoutes(): void
    {
        Route::post(config('variza.webhook_path', 'variza/webhook'), WebhookController::class)
            ->middleware(VerifyVarizaWebhookSignature::class)
            ->name('variza.webhook');
    }
}
