<?php

namespace YasserBenaioua\Chargily;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use YasserBenaioua\Chargily\Commands\ChargilyCommand;

class ChargilyServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('chargily-epay-laravel')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_chargily-epay-laravel_table')
            ->hasCommand(ChargilyCommand::class);
    }
}
