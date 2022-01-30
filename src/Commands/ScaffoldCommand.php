<?php

namespace Usanzadunje\Scaffold\Commands;

use Illuminate\Console\Command;
use Usanzadunje\Scaffold\Presets\BrowserSync;
use Usanzadunje\Scaffold\Presets\Docker;
use Usanzadunje\Scaffold\Presets\Vue;
use Usanzadunje\Scaffold\Presets\VueRouter;
use Usanzadunje\Scaffold\Presets\Vuex;

class ScaffoldCommand extends Command
{
    public $signature = 'scaffold';

    public $description = 'Scaffold your application based on provided templates.';

    public function handle(): int {
        $this->installBrowserSync();
        $this->checkIfUserWantsVue();
        $this->checkIfUserWantsDocker();

        return self::SUCCESS;
    }

    /**
     * Installing BrowserSync with webpack.
     *
     * @return void
     */
    public function installBrowserSync(): void {
        BrowserSync::install();
        $this->info('Webpack BrowserSync installed successfully.');
    }

    /**
     * Asking user and checking whether they are going to use Vue as frontend.
     *
     * @return bool
     */
    public function checkIfUserWantsVue(): bool {
        $wantsVue = $this->ask('Do you want Vue 3 as your frontend? (yes/no)', 'no');

        if($wantsVue = $this->isPositiveAnswer($wantsVue)) {
            $router = $this->choice(
                'Choose routing for your application?',
                ['None', 'Vue Router', 'Inertia'],
                0,
            );
            $stateManager = $this->choice(
                'Choose state manager for your application?',
                ['None', 'Vuex'],
                0,
            );

            $this->vue($router, $stateManager);
        }

        return $wantsVue;
    }

    /**
     * Asking user and checking whether they are going to use Docker environment.
     *
     * @return bool
     */
    public function checkIfUserWantsDocker(): bool {
        $wantsDocker = $this->ask('Do you want Docker inside your project? (yes/no)', 'no');

        if($wantsDocker = $this->isPositiveAnswer($wantsDocker)) {
            $this->docker();
        }

        return $wantsDocker;
    }

    /**
     * Install Vue scaffolding.
     *
     * @return void
     */
    private function vue(string $router, string $stateManager) {
        Vue::install();
        $this->info('Vue scaffolding installed successfully.');

        if($router === 'Vue Router') {
            VueRouter::install();
            $this->info('Vue Router scaffolding installed successfully.');
        }

        if($stateManager === 'Vuex') {
            Vuex::install();
            $this->info('Vuex scaffolding installed successfully.');
        }
    }

    /**
     * Install Docker scaffolding.
     *
     * @return void
     */
    private function docker() {
        Docker::install();

        $this->info('Docker scaffolding installed successfully.');
    }

    /**
     * Check whether user selected positive answer.
     *
     * @param string $answer
     * @return bool
     */
    private function isPositiveAnswer(string $answer): bool {
        return in_array($answer, ['yes', 'ye', 'y', 1]);
    }
}
