<?php

namespace Usanzadunje\Scaffold\Presets;

class BrowserSync extends Preset
{
    /**
     * Initiate BrowserSync scaffolding.
     *
     * @return void
     */
    public static function install(): void {
        // Bootstrapping
        static::updateNodePackages();
        static::updateWebpackConfiguration();
    }

    /**
     * Add node packages for BrowserSync.
     *
     * @param array $packages
     * @return array
     */
    protected static function updatePackageArray(array $packages): array {
        return [
                "browser-sync" => "^2.27.7",
                "browser-sync-webpack-plugin" => "^2.3.0",
            ] + $packages;
    }

    /**
     * Update the Webpack configuration.
     *
     * @return void
     */
    protected static function updateWebpackConfiguration() {
        copy(__DIR__ . '/bs-stubs/webpack.mix.js', base_path('webpack.mix.js'));
    }
}