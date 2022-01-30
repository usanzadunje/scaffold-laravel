<?php

namespace Usanzadunje\Scaffold\Presets;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;

class Vue extends Preset
{
    private static string $router;
    private static string $stateManager;

    public function __construct(string $router, string $stateManager) {
        static::$router = $router;
        static::$stateManager = $stateManager;
    }

    /**
     * Initiate Vue scaffolding.
     *
     * @param string $router
     * @param string $stateManager
     * @return void
     */
    public static function install(): void {
        static::ensureDirectoriesExist();
        static::updateNodePackages();
        static::updateNodePackages(false);
        static::updateWebpackConfiguration();
        static::updateBootstrapping();
        if(static::$router === 'Vue Router') {
            static::updateVueRouterBootstrapping();
        }
        if(static::$stateManager === 'Vuex') {
            static::updateVuexBootstrapping();
        }
        static::updateComponent();
        static::removeNodeModules();
    }

    /**
     * Ensure directories we need actually exists in project.
     *
     * @param string $router
     * @param string $stateManager
     * @return void
     */
    protected static function ensureDirectoriesExist(): void {
        (new Filesystem)->ensureDirectoryExists(resource_path('js'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/components'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/composables'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/views'));
        if(static::$router === 'Vue Router') {
            (new Filesystem)->ensureDirectoryExists(resource_path('js/router'));
        }
        if(static::$stateManager === 'Vuex') {
            (new Filesystem)->ensureDirectoryExists(resource_path('js/store'));
        }
    }

    /**
     * Add node packages for vue and its assets.
     *
     * @param array $packages
     * @param string $configurationKey
     * @return array
     */
    protected static function updatePackageArray(array $packages, string $configurationKey): array {
        $vuePackages = [
            'dependencies' => [
                "vue" => "^3.2.29",
                "path" => "^0.12.7",
            ],
            'devDependencies' => [
                "@vue/compiler-sfc" => "^3.2.29",
                "browser-sync" => "^2.27.7",
                "browser-sync-webpack-plugin" => "^2.3.0",
                "vue-loader" => "^16.8.3",
            ],
        ];
        $routerPackages = [
            'dependencies' => [],
            'devDependencies' => [],
        ];
        $stateManagerPackages = [
            'dependencies' => [],
            'devDependencies' => [],
        ];

        if(static::$router === 'Vue Router') {
            $routerPackages['dependencies'] = [
                "vue-router" => "^4.0.12",
            ];
        }
        if(static::$router === 'Inertia') {
            $routerPackages['dependencies'] = [
                "@inertiajs/inertia" => "^0.11.0",
                "@inertiajs/inertia-vue3" => "^0.6.0",
                "@inertiajs/progress" => "^0.2.7",
            ];
        }

        if(static::$stateManager === 'Vuex') {
            $stateManagerPackages['dependencies'] = [
                "vuex" => "^4.0.2",
                "vuex-persistedstate" => "^4.1.0",
            ];
        }

        return array_merge(
            $vuePackages[$configurationKey],
            $routerPackages[$configurationKey],
            $stateManagerPackages[$configurationKey],
            $packages
        );
    }

    /**
     * Update the Webpack configuration.
     *
     * @return void
     */
    protected static function updateWebpackConfiguration() {
        copy(__DIR__ . '/vue-stubs/webpack.mix.js', base_path('webpack.mix.js'));
    }

    /**
     * Bootstrap Vue app.
     *
     * @return void
     */
    protected static function updateBootstrapping() {
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
     * Bootstrap Vue Router.
     *
     * @return void
     */
    protected static function updateVueRouterBootstrapping() {
        copy(__DIR__ . '/vue-stubs/router/app.js', resource_path('js/app.js'));
        copy(__DIR__ . '/vue-stubs/router/Welcome.vue', resource_path('js/views/Welcome.vue'));
        copy(__DIR__ . '/vue-stubs/router/index.js', resource_path('js/router/index.js'));
    }

    /**
     * Bootstrap Vuex.
     *
     * @return void
     */
    protected static function updateVuexBootstrapping() {
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

        if(static::$router === 'Vue Router') {
            $replaced = str_replace(
                "import { useRouter }       from 'vue-router';",
                "import { useRouter }       from 'vue-router';\nimport { useStore }       from 'vuex';",
                file_get_contents(resource_path('js/views/Welcome.vue'))
            );
            $replaced = str_replace(
                "const router = useRouter();",
                "const router = useRouter();\n\t\tconst store = useStore();",
                $replaced
            );
            $replaced = str_replace(
                "router,",
                "router,\n\t\t\tstore,",
                $replaced
            );
            $replaced = str_replace(
                "Welcome!!!",
                "Welcome!!!\n\t\t<div>Testing vuex store: {{ store.getters['module/test']}}</div>",
                $replaced
            );
            file_put_contents(
                resource_path('js/views/Welcome.vue'),
                $replaced
            );
        }
    }

    /**
     * Update the App component.
     *
     * @return void
     */
    protected static function updateComponent() {
        copy(
            __DIR__ . '/vue-stubs/App.vue',
            resource_path('js/App.vue')
        );

        if(static::$router === 'Vue Router') {
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
}