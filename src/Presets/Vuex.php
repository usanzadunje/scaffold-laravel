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
    public static function install(): void {
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
    protected static function ensureDirectoriesExist(): void {
        (new Filesystem)->ensureDirectoryExists(resource_path('js/store'));
    }

    /**
     * Add node packages for Vuex.
     *
     * @param array $packages
     * @param string $configurationKey
     * @return array
     */
    protected static function updatePackageArray(array $packages, string $configurationKey): array {
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
    protected static function updateBootstrapping() {
        File::copyDirectory(__DIR__ . '/vue-stubs/store', resource_path('js/store'));

        $replaced = str_replace(
            "const app = createApp(App)",
            "const app = createApp(App)\n\t.use(store)",
            file_get_contents(resource_path('js/app.js'))
        );

        file_put_contents(
            resource_path('js/app.js'),
            str_replace(
                "import App from './App.vue';",
                "import App from './App.vue';\n\nimport store from './store';",
                $replaced
            )
        );
    }

    /**
     * Add vuex example into 'Welcome.vue' view file.
     *
     * @return void
     */
    protected static function updateWelcomeView() {
        if(file_exists(resource_path('js/views/Welcome.vue'))) {
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