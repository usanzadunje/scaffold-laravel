<?php

namespace Usanzadunje\Scaffold\Presets;

use Illuminate\Filesystem\Filesystem;

class Vite extends Preset
{
    private static bool $isVueInstalled;
    private static bool $isVueRouterInstalled;
    private static bool $isVuexInstalled;

    /**
     * Initiate Vite scaffolding.
     *
     * @return void
     */
    public static function install(): void
    {
        if (file_exists(resource_path('js/app.js'))) {
            static::$isVueInstalled = is_dir(resource_path('js/components'));
            static::$isVueRouterInstalled = is_dir(resource_path('js/router'));
            static::$isVuexInstalled = is_dir(resource_path('js/store'));
        }

        // Bootstrapping
        static::updateNodePackages();
        static::updateNodeScripts();
        static::updateBootstrapping();
        static::updateViteConfiguration();
    }

    /**
     * Add node packages for Vite and remove ones for Webpack.
     *
     * @param array $packages
     * @return array
     */
    protected static function updatePackageArray(array $packages): array
    {
        static::unsetWebpackPackagesFromPackageArray($packages);

        $viteCore = ["vite" => "^2.7.13",];
        $viteVue = static::$isVueInstalled ? ["@vitejs/plugin-vue" => "^2.1.0",] : [];

        return array_merge($viteCore, $viteVue, $packages);
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
     * Bootstrap Vite.
     *
     * @return void
     */
    protected static function updateBootstrapping(): void
    {
        copy(__DIR__ . '/vite-stubs/vite.config.js', base_path('vite.config.js'));

        if (file_exists(base_path('webpack.mix.js'))) {
            (new Filesystem())->delete(base_path('webpack.mix.js'));
        }
        if (file_exists(base_path('webpack.config.js'))) {
            (new Filesystem())->delete(base_path('webpack.config.js'));
        }

        if (file_exists(resource_path('views/app.blade.php'))) {
            $replaced = str_replace(
                "<link href=\"{{ mix('/css/app.css') }}\" rel=\"stylesheet\"/>",
                "",
                file_get_contents(resource_path('views/app.blade.php'))
            );
            $replaced = str_replace(
                "<script src=\"{{ mix('/js/app.js') }}\" defer></script>",
                "@vite",
                $replaced
            );

            file_put_contents(
                resource_path('views/app.blade.php'),
                $replaced
            );
        }

        if (static::$isVueInstalled) {
            $appFile = file_get_contents(resource_path('js/app.js'));

            file_put_contents(
                resource_path('js/app.js'),
                "import '../css/app.css';\n\n" . $appFile
            );
        }
    }

    /**
     * Updating 'vite.config.js' file.
     *
     * @return void
     */
    protected static function updateViteConfiguration(): void
    {
        if (static::$isVueInstalled) {
            $replaced = str_replace(
                "from 'vite';",
                "from 'vite';\nimport vue              from '@vitejs/plugin-vue';",
                file_get_contents(base_path('vite.config.js'))
            );

            $replaced = str_replace(
                "'axios',",
                "'vue',\n\t\t\t'axios',",
                $replaced
            );

            $replaced = str_replace(
                "plugins: [",
                "plugins: [vue()",
                $replaced
            );

            file_put_contents(
                base_path('vite.config.js'),
                $replaced
            );
        }
        if (static::$isVueRouterInstalled) {
            $replaced = str_replace(
                "'vue',",
                "'vue',\n\t\t\t'vue-router',",
                file_get_contents(base_path('vite.config.js'))
            );

            file_put_contents(
                base_path('vite.config.js'),
                $replaced
            );
        }
        if (static::$isVuexInstalled) {
            $replaced = str_replace(
                "'vue',",
                "'vue',\n\t\t\t'vuex',\n\t\t\t'vuex-persistedstate',",
                file_get_contents(base_path('vite.config.js'))
            );

            file_put_contents(
                base_path('vite.config.js'),
                $replaced
            );
        }
    }

    /**
     * Unsets webpack packages from packages array.
     *
     * @param array $packages
     * @return void
     */
    private static function unsetWebpackPackagesFromPackageArray(array &$packages): void
    {
        $packagesToRemove = ['laravel-mix', 'browser-sync', 'browser-sync-webpack-plugin', 'vue-loader'];
        foreach ($packagesToRemove as $key) {
            unset($packages[$key]);
        }
    }
}
