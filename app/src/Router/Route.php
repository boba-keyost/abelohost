<?php

namespace Router;

use Extensions\WithConfig;
use Extensions\WithDB;
use Extensions\WithLogger;

interface Route extends WithLogger, WithDB, WithConfig
{
    public function run(array $parameters = [], mixed $body = null): void;
    public function handle(array $parameters = [], mixed $body = null): mixed;
}
