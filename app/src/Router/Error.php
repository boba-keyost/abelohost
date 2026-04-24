<?php

namespace Router;

class Error extends \App\Error
{
    protected const string PREFIX = "Router";

    public static function notFound(string $message): static
    {
        return new static($message, 404);
    }
}
