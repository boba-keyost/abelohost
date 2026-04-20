<?php

namespace Router;

interface Route {
    public function run(array $parameters = [], mixed $body = null): void;
}