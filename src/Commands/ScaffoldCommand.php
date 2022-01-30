<?php

namespace Usanzadunje\Scaffold\Commands;

use Illuminate\Console\Command;
use Usanzadunje\Scaffold\Presets\Vue;

class ScaffoldCommand extends Command
{
    public $signature = 'scaffold';

    public $description = 'Scaffold your application based on provided templates.';

    public function handle(): int {
        $vueFrontendAnswer = $this->ask('Do you want Vue 3 as your frontend? (yes/no)', 'no');

        if($this->isPositiveAnswer($vueFrontendAnswer)) {
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


        $this->comment('Please run "npm install && npm run dev" to compile your fresh scaffolding.');
        $this->comment('Additionally you cold run "php artisan serve" and "npm run watch" to serve your application.');

        return self::SUCCESS;
    }

    /**
     * Install Vue scaffolding.
     *
     * @return void
     */
    private function vue(string $router, string $stateManager) {
        (new Vue($router, $stateManager))::install();

        $this->info('Vue scaffolding installed successfully.');
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
