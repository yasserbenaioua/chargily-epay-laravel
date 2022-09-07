<?php

namespace YasserBenaioua\Chargily\Exceptions;

use Exception;

class ValidationException extends Exception
{
    public function __construct($errors)
    {
        $this->message = implode(', ', $errors->all());
    }
}
