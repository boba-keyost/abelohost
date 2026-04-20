<?php

namespace Router\Routes;

use Renderers\RendererType;
use Router\BaseRoute;
use Renderers\Renderer;
use Router\RouteAttribute;

#[RouteAttribute("", "/", ["renderer" => RendererType::Html])]
class IndexRoute extends BaseRoute {
    public function run(array $parameters = [], mixed $body = null): void
    {
        $this->respond(["parameters" => $parameters, "body" => $body]);
    }
}