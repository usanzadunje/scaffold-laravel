<?php

namespace Usanzadunje\Scaffold\Presets;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;

class Vite extends Preset
{
    /**
     * Initiate Docker scaffolding.
     *
     * @return void
     */
    public static function install(): void {
        // Bootstrapping
        static::updateNodePackages();
    }

    /**
     * Add node packages for Vite and remove ones for Webpack.
     *
     * @param array $packages
     * @return array
     */
    protected static function updatePackageArray(array $packages): array {
        static::unsetPackagesFromPackageArray($packages);
        // INSTALL VITE

        return $packages;
    }

    /**
     * Unsets packages that are used for webpack from packages array.
     *
     * @param array $packages
     * @return void
     */
    private static function unsetPackagesFromPackageArray(array &$packages): void {
        $packagesToRemove = ['laravel-mix', 'browser-sync', 'browser-sync-webpack-plugin'];
        foreach($packagesToRemove as $key){
            unset($packages[$key]);
        }
    }
}


