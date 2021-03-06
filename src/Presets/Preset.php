<?php

namespace Usanzadunje\Scaffold\Presets;

use Illuminate\Filesystem\Filesystem;

class Preset
{
    /**
     * Update scripts in "package.json" file.
     *
     * @return void
     */
    protected static function updateNodeScripts(): void
    {
        if (! file_exists(base_path('package.json'))) {
            return;
        }

        $configurationKey = 'scripts';

        $jsonContent = json_decode(file_get_contents(base_path('package.json')), true);

        $jsonContent[$configurationKey] = static::updateScriptsArray(
            array_key_exists($configurationKey, $jsonContent) ? $jsonContent[$configurationKey] : []
        );

        ksort($jsonContent[$configurationKey]);

        file_put_contents(
            base_path('package.json'),
            json_encode($jsonContent, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . PHP_EOL
        );
    }

    /**
     * Update packages in "package.json" file.
     *
     * @param bool $dev
     * @return void
     */
    protected static function updateNodePackages(bool $dev = true): void
    {
        if (! file_exists(base_path('package.json'))) {
            return;
        }

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        $jsonContent = json_decode(file_get_contents(base_path('package.json')), true);

        $jsonContent[$configurationKey] = static::updatePackageArray(
            array_key_exists($configurationKey, $jsonContent) ? $jsonContent[$configurationKey] : [],
            $configurationKey
        );

        ksort($jsonContent[$configurationKey]);

        file_put_contents(
            base_path('package.json'),
            json_encode($jsonContent, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . PHP_EOL
        );
    }

    /**
     * Remove the installed Node modules.
     *
     * @return void
     */
    protected static function removeNodeModules()
    {
        tap(new Filesystem(), function (Filesystem $files) {
            $files->deleteDirectory(base_path('node_modules'));

            $files->delete(base_path('package-lock.json'));
        });
    }
}
