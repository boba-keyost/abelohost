<?php

namespace Router\Routes;

use Router\BaseRoute;
use Router\Route;
use Router\RouteAttribute;

#[RouteAttribute("", "/*")]
class DefaultRoute extends BaseRoute {
    public function run(array $parameters = [], mixed $body = null): void
    {
        header("Content-Type: text/plain");
        echo file_get_contents("/tmp/xdebug.log");
    }
}