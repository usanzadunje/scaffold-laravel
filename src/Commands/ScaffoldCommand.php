<?php

namespace Usanzadunje\Scaffold\Commands;

use Illuminate\Console\Command;

class ScaffoldCommand extends Command
{
    public $signature = 'scaffoldl';

    public $description = 'Scaffold your application based on provided templates.';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
