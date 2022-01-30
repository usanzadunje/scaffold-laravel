<?php

namespace Usanzadunje\Scaffold\Presets;

use Illuminate\Filesystem\Filesystem;

class VueRouter extends Preset
{
    /**
     * Initiate Vue Router scaffolding.
     *
     * @return void
     */
    public static function install(): void
    {
        // Bootstrapping
        static::ensureDirectoriesExist();
        static::updateNodePackages(false);
        static::updateBootstrapping();
        static::updateComponent();
    }

    /**
     * Ensure directories we need actually exists in project.
     *
     * @return void
     */
    protected static function ensureDirectoriesExist(): void
    {
        (new Filesystem())->ensureDirectoryExists(resource_path('js/router'));
    }

    /**
     * Add node packages for Vue Router.
     *
     * @param array $packages
     * @return array
     */
    protected static function updatePackageArray(array $packages): array
    {
        return [
                "vue-router" => "^4.0.12",
            ] + $packages;
    }

    /**
     * Bootstrap Vue app with Vue Router.
     *
     * @return void
     */
    protected static function updateBootstrapping()
    {
        copy(__DIR__ . '/vue-stubs/router/app.js', resource_path('js/app.js'));
        copy(__DIR__ . '/vue-stubs/router/Welcome.vue', resource_path('js/views/Welcome.vue'));
        copy(__DIR__ . '/vue-stubs/router/index.js', resource_path('js/router/index.js'));
    }

    /**
     * Update the App component.
     *
     * @return void
     */
    protected static function updateComponent()
    {
        file_put_contents(
            resource_path('js/App.vue'),
            str_replace(
                '<div></div>',
                '<router-view/>',
                file_get_contents(resource_path('js/App.vue'))
            )
        );
    }
}
