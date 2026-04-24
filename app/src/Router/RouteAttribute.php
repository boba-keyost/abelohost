<?php

namespace Router;

#[\Attribute]
final class RouteAttribute
{
    public string $method;
    public mixed $path;
    public mixed $parameters;

    public function __construct(string $method, string $path, array $parameters = [])
    {
        $this->method = $method;
        $this->path = $path;
        $this->parameters = $parameters;
    }

    public const string METHOD_GET = 'GET';
    public const string METHOD_POST = 'POST';
}
