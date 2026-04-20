<?php

namespace Router;

#[\Attribute]
final class RouteAttribute{
    public string $method{
        get {
            return $this->method;
        }
    }
    public mixed $path {
        get {
            return $this->path;
        }
    }
    public mixed $parameters {
        get {
            return $this->parameters;
        }
    }

    public function __construct(string $method, string $path, array $parameters = []) {
        $this->method = $method;
        $this->path = $path;
        $this->parameters = $parameters;
    }

    public const string METHOD_GET = 'GET';
    public const string METHOD_POST = 'POST';
}