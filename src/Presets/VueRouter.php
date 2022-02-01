<?php

namespace Usanzadunje\Scaffold\Presets;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;

class VueRouter extends Preset
{
    /**
     * Initiate Vue Router scaffolding.
     *
     * @param $wantsMiddleware
     * @return void
     */
    public static function install($wantsMiddleware): void
    {
        // Bootstrapping
        static::ensureDirectoriesExist();
        static::updateNodePackages(false);
        static::updateBootstrapping($wantsMiddleware);
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
     * @param bool $wantsMiddleware
     * @return void
     */
    protected static function updateBootstrapping(bool $wantsMiddleware)
    {
        $matches = null;
        preg_match('/createApp\(([^)]+)\)/', file_get_contents(resource_path('js/app.js')), $matches);

        copy(__DIR__ . '/vue-stubs/router/Welcome.vue', resource_path('js/views/Welcome.vue'));
        copy(__DIR__ . '/vue-stubs/router/index.js', resource_path('js/router/index.js'));

        $replaced = str_replace(
            "from 'vue';",
            "from 'vue';\n\nimport ExampleApp from './ExampleApp.vue';\n\nimport router from './router';",
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

        if ($wantsMiddleware) {
            static::generateMiddlewareScaffolding();
        }
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

    /**
     * Generates new and changes existing content to adjust it for middleware usage.
     *
     * @return void
     */
    private static function generateMiddlewareScaffolding()
    {
        $middlewareNavigationGuard = file_get_contents(__DIR__ . '/vue-stubs/router/middlewareNavigationGuard.js');

        copy(__DIR__ . '/vue-stubs/router/middlewarePipeline.js', resource_path('js/router/middlewarePipeline.js'));
        File::copyDirectory(__DIR__ . '/vue-stubs/router/middlewares', resource_path('js/middlewares'));

        $replaced = str_replace(
            "from 'vue-router';",
            "from 'vue-router';
            \nimport middlewarePipeline from './middlewarePipeline';
            \nimport middleware from '@/middlewares/middleware';",
            file_get_contents(resource_path('js/router/index.js'))
        );

        $replaced = str_replace(
            "name: 'welcome',",
            "name: 'welcome',\n\t\tmeta: {\n\t\t\tmiddleware: [middleware],\n\t\t},",
            $replaced
        );

        $replaced = str_replace(
            "export default router;",
            "$middlewareNavigationGuard\n\nexport default router;",
            $replaced
        );

        file_put_contents(
            resource_path('js/router/index.js'),
            $replaced
        );
    }
}
