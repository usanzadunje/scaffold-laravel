<?php

namespace Usanzadunje\Scaffold\Commands;

use Illuminate\Console\Command;
use Usanzadunje\Scaffold\Presets\BrowserSync;
use Usanzadunje\Scaffold\Presets\Docker;
use Usanzadunje\Scaffold\Presets\Vite;
use Usanzadunje\Scaffold\Presets\Vue;
use Usanzadunje\Scaffold\Presets\VueRouter;
use Usanzadunje\Scaffold\Presets\Vuex;

class ScaffoldCommand extends Command
{
    public $signature = 'scaffold {preset : Name of the preset to scaffold application with}';

    public $description = 'Scaffold your application based on provided templates.';

    public function handle(): int
    {
        switch($this->argument('preset'))
        {
            case 'vue':
                $this->vue();
                break;
            case 'vue-router':
                $this->vueRouter();
                break;
            case 'vuex':
                $this->vuex();
                break;
            case 'vite':
                $this->vite();
                break;
            case 'docker':
                $this->docker();
                break;
        }

        return self::SUCCESS;
    }

    /**
     * Install Vue scaffolding.
     *
     * @return void
     */
    private function vue()
    {
        Vue::install();
        $this->info('Vue scaffolding installed successfully.');

        $router = $this->choice(
            'Choose routing for your application',
            ['None', 'Vue Router', 'Inertia'],
            0,
        );

        if($router === 'Vue Router')
        {
            $this->vueRouter();
        }

        $stateManager = $this->choice(
            'Choose state manager for your application',
            ['None', 'Vuex'],
            0,
        );

        if($stateManager === 'Vuex')
        {
            $this->vuex();
        }
    }

    /**
     * Install Vue Router scaffolding.
     *
     * @return void
     */
    private function vueRouter()
    {
        $wantsMiddleware = $this->ask('Do you want middleware scaffolding as well?', 'no');

        VueRouter::install($this->isPositiveAnswer($wantsMiddleware));
        $this->info('Vue Router scaffolding installed successfully.');
    }

    /**
     * Install Vuex scaffolding.
     *
     * @return void
     */
    private function vuex()
    {
        Vuex::install();
        $this->info('Vuex scaffolding installed successfully.');
    }

    /**
     * Install Vite scaffolding.
     *
     * @return void
     */
    private function vite()
    {
        Vite::install();

        $this->info('Vite scaffolding installed successfully.');
    }

    /**
     * Install Docker scaffolding.
     *
     * @return void
     */
    private function docker()
    {
        if($this->isPositiveAnswer($this->ask('Do you want browser sync webpack plugin as well?', 'no')))
        {
            BrowserSync::install();

            $this->info('Browser sync successfully added.');
        }
        Docker::install();

        $this->info('Docker scaffolding installed successfully.');
    }

    /**
     * Check whether user selected positive answer.
     *
     * @param string $answer
     * @return bool
     */
    private function isPositiveAnswer(string $answer): bool
    {
        return in_array($answer, ['yes', 'ye', 'y', 1]);
    }
}
