<?php

namespace App;

use Extensions\LoggerExtension;
use Router\Router;
use Router\Routes\IndexRoute;
use Router\Routes\ScssRoute;
use Throwable;

class App extends LoggerExtension{
    protected Router $router;

    /**
     * @throws \Exception
     */
    public function __construct() {
        $this->router = new Router();
        $this->setLogger(Logger::getDefault());
        $this->router->setLogger($this->logger);
        $this->router->registerRoutes(
            new ScssRoute(),
            new IndexRoute(),
        );
    }

    public function run(): void
    {
        try {
            $this->router->run();
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage());
            echo $exception->getMessage();
        }
    }
}