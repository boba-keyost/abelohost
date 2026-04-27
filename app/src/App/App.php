<?php

namespace App;

use Config\Config;
use DB\DB;
use Exception;
use Extensions\ConfigExtension;
use Extensions\DBExtension;
use Extensions\LoggerExtension;
use Router\Router;
use Router\Routes\CategoryRoute;
use Router\Routes\IndexRoute;
use Router\Routes\PostRoute;
use Router\Routes\ScssRoute;
use Throwable;

class App
{
    use LoggerExtension;
    use DBExtension;
    use ConfigExtension;

    protected Router $router;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $config = Config::defaultConfig();
        $this->setConfig($config);
        $dbGetter = fn () => new DB($this->getConfig());
        $this->setDbGetter($dbGetter);
        $this->router = new Router();
        $this->setLogger(Logger::getDefault());
        $this->router->setLogger($this->logger);
        $this->router->setDbGetter($dbGetter);
        $this->router->setConfig($this->getConfig());
        $this->router->registerRoutes(
            new ScssRoute(),
            new IndexRoute(),
            new CategoryRoute(),
            new PostRoute(),
        );
    }

    public function run(): void
    {
        session_start();
        try {
            $this->router->run(null, null, null, null, null);
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage());
            echo $exception->getMessage();
        }
        session_write_close();
    }
}
