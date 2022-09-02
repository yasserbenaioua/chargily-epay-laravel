<?php

namespace YasserBenaioua\Chargily\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \YasserBenaioua\Chargily\Chargily
 */
class Chargily extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \YasserBenaioua\Chargily\Chargily::class;
    }
}
