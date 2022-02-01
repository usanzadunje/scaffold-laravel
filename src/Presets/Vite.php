<?php

namespace Usanzadunje\Scaffold\Presets;

class Vite extends Preset
{
    private static bool $isVueInstalled;
    /**
     * Initiate Docker scaffolding.
     *
     * @return void
     */
    public static function install(): void
    {
        if(file_exists(resource_path('js/app.js'))){
            static::$isVueInstalled = true;
        }
        // Bootstrapping
        static::updateNodePackages();
        static::updateBootstrapping();
    }

    /**
     * Add node packages for Vite and remove ones for Webpack.
     *
     * @param array $packages
     * @return array
     */
    protected static function updatePackageArray(array $packages): array
    {
        static::unsetPackagesFromPackageArray($packages);

        // INSTALL VITE

        return $packages;
    }

    /**
     * Add scripts for Vite and remove ones for Webpack.
     *
     * @param array $scripts
     * @return array
     */
    protected static function updateScriptsArray(array $scripts): array
    {
        return [
            "dev" => "vite",
            "prod" => "vite build",
        ];
    }

    /**
     * Bootstrap Vite and remove Webpack.
     *
     * @return void
     */
    protected static function updateBootstrapping(): void {}

    /**
     * Unsets packages that are used for webpack from packages array.
     *
     * @param array $packages
     * @return void
     */
    private static function unsetPackagesFromPackageArray(array &$packages): void
    {
        $packagesToRemove = ['laravel-mix', 'browser-sync', 'browser-sync-webpack-plugin'];
        foreach($packagesToRemove as $key)
        {
            unset($packages[$key]);
        }
    }
}
