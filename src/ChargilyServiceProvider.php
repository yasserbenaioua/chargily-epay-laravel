<?php

namespace YasserBenaioua\Chargily;

use Illuminate\Support\Facades\Route;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use YasserBenaioua\Chargily\Http\Controllers\ChargilyWebhookController;

class ChargilyServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('chargily')
            ->hasConfigFile('chargily')
            ->hasMigration('create_chargily_webhook_calls_table')
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToStarRepoOnGitHub('yasserbenaioua/chargily-epay-laravel')
                    ->endWith(function (InstallCommand $installCommand) {
                        $installCommand->info('Thank you very much for installing this package');
                    });
            });
    }

    public function bootingPackage()
    {
        Route::macro('chargilyWebhook', function ($url) {
            return Route::post($url, ChargilyWebhookController::class);
        });
    }
}
