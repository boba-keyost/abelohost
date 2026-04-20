<?php

use App\App;

require_once __DIR__ . '/../vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('xdebug.force_display_errors', 1);
error_reporting(E_ALL);

try {
    $app = new App();

    $app->run();
} catch (Throwable $exception) {
    \Renderers\RendererFabric::get()->render($exception);
}