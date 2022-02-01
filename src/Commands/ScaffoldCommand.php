<?php

namespace Usanzadunje\Scaffold\Commands;

use Illuminate\Console\Command;
use Usanzadunje\Scaffold\Presets\Docker;
use Usanzadunje\Scaffold\Presets\Vite;
use Usanzadunje\Scaffold\Presets\Vue;
use Usanzadunje\Scaffold\Presets\VueRouter;
use Usanzadunje\Scaffold\Presets\Vuex;

class ScaffoldCommand extends Command
{
    //public $signature = 'scaffold {preset: Name of the preset to scaffold application with} {--m|middleware: Whether middleware scaffolding should be included with vue-router}';
    public $signature = 'scaffold {preset} {--m|middleware}';

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
                // vite
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
        $stateManager = $this->choice(
            'Choose state manager for your application',
            ['None', 'Vuex'],
            0,
        );

        if($router === 'Vue Router')
        {
            $this->vueRouter();
        }

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
        VueRouter::install($this->option('middleware'));
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
     * Install Docker scaffolding.
     *
     * @return void
     */
    private function docker()
    {
        Docker::install();

        $this->info('Docker scaffolding installed successfully.');
    }
    //
    ///**
    // * Check whether user selected positive answer.
    // *
    // * @param string $answer
    // * @return bool
    // */
    //private function isPositiveAnswer(string $answer): bool
    //{
    //    return in_array($answer, ['yes', 'ye', 'y', 1]);
    //}
}
