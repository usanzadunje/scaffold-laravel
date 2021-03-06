<?php

namespace Usanzadunje\Scaffold\Presets;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;

class Vuex extends Preset
{
    /**
     * Initiate Vuex scaffolding.
     *
     * @return void
     */
    public static function install(): void
    {
        // Bootstrapping
        static::ensureDirectoriesExist();
        static::updateNodePackages(false);
        static::updateBootstrapping();
        static::updateWelcomeView();
    }

    /**
     * Ensure directories we need actually exists in project.
     *
     * @return void
     */
    protected static function ensureDirectoriesExist(): void
    {
        (new Filesystem())->ensureDirectoryExists(resource_path('js/store'));
    }

    /**
     * Add node packages for Vuex.
     *
     * @param array $packages
     * @param string $configurationKey
     * @return array
     */
    protected static function updatePackageArray(array $packages, string $configurationKey): array
    {
        return [
                "vuex" => "^4.0.2",
                "vuex-persistedstate" => "^4.1.0",
            ] + $packages;
    }

    /**
     * Bootstrap Vue app with Vuex.
     *
     * @return void
     */
    protected static function updateBootstrapping()
    {
        $matches = null;
        preg_match('/createApp\(([^)]+)\)/', file_get_contents(resource_path('js/app.js')), $matches);

        File::copyDirectory(__DIR__ . '/vue-stubs/store', resource_path('js/store'));

        $replaced = str_replace(
            "from 'vue';",
            "from 'vue';\n\nimport store from './store';",
            file_get_contents(resource_path('js/app.js'))
        );

        $replaced = str_replace(
            $matches[0],
            "$matches[0]\n\t.use(store)",
            $replaced
        );

        file_put_contents(
            resource_path('js/app.js'),
            $replaced
        );
    }

    /**
     * Add Vuex example into 'Welcome.vue' view file.
     *
     * @return void
     */
    protected static function updateWelcomeView()
    {
        if (file_exists(resource_path('js/views/Welcome.vue'))) {
            $replace = str_replace(
                "import { useRouter }       from 'vue-router';",
                "import { useRouter }       from 'vue-router';\nimport { useStore }       from 'vuex';",
                file_get_contents(resource_path('js/views/Welcome.vue'))
            );
            $replace = str_replace(
                "const router = useRouter();",
                "const router = useRouter();\n\t\tconst store = useStore();",
                $replace
            );
            $replace = str_replace(
                "router,",
                "router,\n\t\t\tstore,",
                $replace
            );
            $replace = str_replace(
                "Welcome!!!",
                "Welcome!!!\n\t\t<div>Testing vuex store: {{ store.getters['module/test']}}</div>",
                $replace
            );
            file_put_contents(
                resource_path('js/views/Welcome.vue'),
                $replace
            );
        }
    }
}
