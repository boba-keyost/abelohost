<?php

namespace App;

use Config\Config;
use DB\DB;
use Exception;
use Extensions\LoggerExtension;
use Extensions\DBExtension;
use Router\Router;
use Router\Routes\IndexRoute;
use Router\Routes\ScssRoute;
use Throwable;

class App
{
    use LoggerExtension;
    use DBExtension;

    protected Router $router;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $dbGetter = fn() => new DB(Config::defaultConfig());
        $this->setDbGetter($dbGetter);
        $this->router = new Router();
        $this->setLogger(Logger::getDefault());
        $this->router->setLogger($this->logger);
        $this->router->setDbGetter($dbGetter);
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
