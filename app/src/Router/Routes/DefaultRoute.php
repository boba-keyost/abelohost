<?php

namespace Router\Routes;

use Renderers\RendererType;
use Router\BaseRoute;
use Router\RouteAttribute;

#[RouteAttribute("", "/*", ["renderer" => RendererType::Json, "rendererParameters" => ["prettyPrint" => true]])]
class DefaultRoute extends BaseRoute
{
    public function handle(array $parameters = [], mixed $body = null): mixed
    {
        return ["parameters" => $parameters, "body" => $body];
    }
}
