<?php

namespace Config;

class Config extends BaseConfig
{
    #[ConfigAttribute(ConfigAttribute::DEBUG, false)]
    protected bool $debug = false;

    #[ConfigAttribute(ConfigAttribute::DB_HOST, "mysql")]
    protected string $dbHost = "";

    #[ConfigAttribute(ConfigAttribute::DB_PORT, 3306)]
    protected int $dbPort = 3306;

    #[ConfigAttribute(ConfigAttribute::DB_USER, "abelohost")]
    protected string $dbUser = "";

    #[ConfigAttribute(ConfigAttribute::DB_PASSWORD, "")]
    protected string $dbPassword = "";

    #[ConfigAttribute(ConfigAttribute::DB_NAME, "abelohost")]
    protected string $dbName = "";

    #[ConfigAttribute(ConfigAttribute::LOG_LEVEL, 0)]
    protected int $logLevel = 0;
}
