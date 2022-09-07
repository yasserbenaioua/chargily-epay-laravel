<?php

namespace YasserBenaioua\Chargily;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
            ->name('chargily')
            ->hasConfigFile('chargily')
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->askToStarRepoOnGitHub('yasserbenaioua/chargily-epay-laravel')
                    ->endWith(function (InstallCommand $installCommand) {
                        $installCommand->info('Thank you very much for installing this package');
                    });
            });
    }
}
