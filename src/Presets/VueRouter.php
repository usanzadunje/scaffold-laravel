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
        static::updateView();
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
        $matches = [];
        preg_match('/createApp\(([^)]+)\)/', file_get_contents(resource_path('js/app.js')), $matches);

        copy(__DIR__ . '/vue-stubs/router/Welcome.vue', resource_path('js/views/Welcome.vue'));
        copy(__DIR__ . '/vue-stubs/router/index.js', resource_path('js/router/index.js'));

        $replaced = str_replace(
            "from 'vue';",
            "from 'vue';\n\nimport router from './router';\n\nimport ExampleApp from './ExampleApp.vue';",
            file_get_contents(resource_path('js/app.js'))
        );

        $replaced = str_replace(
            $matches[0],
            "createApp(ExampleApp)\n\t.use(router)",
            $replaced
        );

        $replaced = str_replace(
            "app.mount('#app');",
            "router.isReady().then(() => {\n\tapp.mount('#app');\n});",
            $replaced
        );

        file_put_contents(
            resource_path('js/app.js'),
            $replaced
        );
    }

    /**
     * Update the App example view.
     *
     * @return void
     */
    protected static function updateView()
    {
        copy(__DIR__ . '/vue-stubs/router/ExampleApp.vue', resource_path('js/ExampleApp.vue'));
    }
}
