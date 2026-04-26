<?php

namespace Config;

#[\Attribute]
final class ConfigAttribute
{
    public string $name;
    public mixed $defaultValue;

    public function __construct(string $name, mixed $defaultValue = null)
    {
        $this->name = $name;
        $this->defaultValue = $defaultValue;
    }

    public const string DEBUG = "debug";
    public const string LOG_LEVEL = 'log_level';
    public const string DB_HOST = 'db_host';
    public const string DB_PORT = 'db_port';
    public const string DB_USER = 'db_user';
    public const string DB_PASSWORD = 'db_password';
    public const string DB_NAME = 'db_name';
}
