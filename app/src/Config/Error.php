<?php

namespace Config;

class Error extends \App\Error
{
    protected const string PREFIX = "Config";

    public const string ERROR_PROPERTY_SET_RESTRICTED = "properties can't be set";

    public const string ERROR_PROPERTY_UNKNOWN = "unknown config property";
}
