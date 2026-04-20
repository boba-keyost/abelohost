<?php

namespace Router;

class BaseRoute implements Route {
    public function run(array $parameters = [], mixed $body = null): void
    {
        $this->respond("default route");
    }

    public function respond(mixed $response): void{
        echo $response;
    }
}