<?php

namespace App;

use Exception;

class Error extends Exception
{
    protected const string PREFIX = "";

    public function __construct($message, $code = 0, ?Exception $previous = null)
    {
        parent::__construct((static::PREFIX ? "[" . static::PREFIX . "] " : "") . $message, $code, $previous);
    }
}
