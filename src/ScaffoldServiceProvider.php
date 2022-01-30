<?php

namespace Usanzadunje\Scaffold;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Usanzadunje\Scaffold\Commands\ScaffoldCommand;

class ScaffoldServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('scaffold-laravel')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_scaffold-laravel_table')
            ->hasCommand(ScaffoldCommand::class);
    }
}
