<?php

namespace Usanzadunje\Scaffold\Presets;

use Illuminate\Filesystem\Filesystem;

class Vue extends Preset
{
    /**
     * Initiate Vue scaffolding.
     *
     * @return void
     */
    public static function install(): void
    {
        // Bootstrapping
        static::ensureDirectoriesExist();
        static::updateNodePackages();
        static::updateNodePackages(false);
        static::updateWebpackConfiguration();
        static::updateBootstrapping();
        static::updateComponent();
        static::removeNodeModules();
    }

    /**
     * Ensure directories we need actually exists in project.
     *
     * @return void
     */
    protected static function ensureDirectoriesExist(): void
    {
        (new Filesystem())->ensureDirectoryExists(resource_path('js'));
        (new Filesystem())->ensureDirectoryExists(resource_path('js/components'));
        (new Filesystem())->ensureDirectoryExists(resource_path('js/composables'));
        (new Filesystem())->ensureDirectoryExists(resource_path('js/views'));
    }

    /**
     * Add node packages for Vue.
     *
     * @param array $packages
     * @param string $configurationKey
     * @return array
     */
    protected static function updatePackageArray(array $packages, string $configurationKey): array
    {
        $vuePackages = [
            'dependencies' => [
                "vue" => "^3.2.29",
                "path" => "^0.12.7",
            ],
            'devDependencies' => [
                "@vue/compiler-sfc" => "^3.2.29",
                "vue-loader" => "^16.8.3",
            ],
        ];

        return $vuePackages[$configurationKey] + $packages;
    }

    /**
     * Update the Webpack configuration.
     *
     * @return void
     */
    protected static function updateWebpackConfiguration()
    {
        copy(__DIR__ . '/vue-stubs/webpack.mix.js', base_path('webpack.mix.js'));
    }

    /**
     * Bootstrap Vue app.
     *
     * @return void
     */
    protected static function updateBootstrapping()
    {
        copy(__DIR__ . '/vue-stubs/app.js', resource_path('js/app.js'));

        (new Filesystem())->delete(resource_path('js/bootstrap.js'));

        copy(__DIR__ . '/vue-stubs/app.blade.php', resource_path('views/app.blade.php'));
        (new Filesystem())->delete(resource_path('views/welcome.blade.php'));
        file_put_contents(
            base_path('routes/web.php'),
            str_replace(
                'welcome',
                'app',
                file_get_contents(base_path('routes/web.php'))
            )
        );
    }

    /**
     * Update the App component.
     *
     * @return void
     */
    protected static function updateComponent()
    {
        copy(__DIR__ . '/vue-stubs/App.vue', resource_path('js/App.vue'));
    }
}
