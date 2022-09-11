<?php

namespace YasserBenaioua\Chargily\Exceptions;

use Exception;

class InvalidConfig extends Exception
{
    public static function secretKeyNotSet(): InvalidConfig
    {
        return new static('The chargily secret key is not set. Make sure that the `secret` config key is set to the correct value.');
    }
}
