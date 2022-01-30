<?php

namespace Usanzadunje\Scaffold\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Usanzadunje\Scaffold\Scaffold
 */
class Scaffold extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'scaffold-laravel';
    }
}
