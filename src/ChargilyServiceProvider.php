<?php

namespace YasserBenaioua\Chargily;

use Illuminate\Support\Facades\Route;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use YasserBenaioua\Chargily\Http\Controllers\ChargilyWebhookController;

class ChargilyServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('chargily-epay-laravel')
            ->hasConfigFile('chargily')
            ->hasMigration('create_chargily_webhook_calls_table');
    }

    public function bootingPackage()
    {
        Route::macro('chargilyWebhook', function ($url) {
            return Route::post($url, ChargilyWebhookController::class);
        });
    }
}
