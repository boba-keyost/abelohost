<?php
use Router\Router;

require_once __DIR__ . '/../vendor/autoload.php';
try {
    $router = new Router();

    $router->run();
} catch (Throwable $exception) {
    echo $exception->getMessage();
}