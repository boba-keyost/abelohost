<?php

namespace Router;

use Extensions\WithLogger;

interface Route extends WithLogger {
    public function run(array $parameters = [], mixed $body = null): void;
}