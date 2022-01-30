<?php

namespace Usanzadunje\Scaffold\Commands;

use Illuminate\Console\Command;

class ScaffoldCommand extends Command
{
    public $signature = 'scaffold-laravel';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
