<?php

namespace Usanzadunje\Scaffold;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Usanzadunje\Scaffold\Commands\ScaffoldCommand;

class ScaffoldServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('scaffold-laravel')
            ->hasConfigFile()
            ->hasCommand(ScaffoldCommand::class);
    }
}
