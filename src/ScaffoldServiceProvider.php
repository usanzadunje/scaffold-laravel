<?php

namespace Usanzadunje\Scaffold;

use Illuminate\Support\Facades\Blade;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Usanzadunje\Scaffold\Commands\ScaffoldCommand;
use Usanzadunje\Scaffold\Helpers\ViteAssets;

class ScaffoldServiceProvider extends PackageServiceProvider
{
    //public function boot()
    //{
    //    Blade::directive('vite', [ViteAssets::class, 'generate']);
    //}

    public function configurePackage(Package $package): void
    {
        $package
            ->name('scaffold-laravel')
            ->hasCommand(ScaffoldCommand::class);
    }
}
