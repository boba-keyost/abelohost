<?php

namespace DB;

use Config\Config;
use DB\Models\Categories;
use DB\Models\Posts;
use PDO;
use PDOException;
use PDOStatement;

class DB
{
    protected Config $config;

    protected ?PDO $pdo = null;

    protected ?Posts $posts = null;
    protected ?Categories $categories = null;

    protected static ?DB $default = null;

    public static function getDefault(): static
    {
        if (is_null(static::$default)) {
            static::$default = new static(Config::defaultConfig());
        }
        return static::$default;
    }

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function posts(): Posts
    {
        if (is_null($this->posts)) {
            $this->posts = new Posts($this);
        }
        return $this->posts;
    }

    public function categories(): Categories
    {
        if (is_null($this->categories)) {
            $this->categories = new Categories($this);
        }
        return $this->categories;
    }

    protected function pdo(): PDO
    {
        if (is_null($this->pdo)) {
            $dsn = sprintf(
                'mysql:host=%s;port=%d;dbname=%s',
                $this->config->dbHost,
                $this->config->dbPort,
                $this->config->dbName,
            );
            $this->pdo = new PDO($dsn, $this->config->dbUser, $this->config->dbPassword);
        }
        return $this->pdo;
    }

    protected function prepare(string $query, array | ParamsList $params = []): PDOStatement
    {
        $pdo = $this->pdo();
        $st = $pdo->prepare($query);
        if (!empty($params)) {
            $this->preparePDOParamsList($params)->bindTo($st);
        }

        return $st;
    }

    /**
     * @throws Error
     */
    public function execute(string $query, array | ParamsList $params = []): PDOStatement | false
    {
        $st = $this->prepare($query, $params);
        try {
            $success = $st->execute();
        } catch (PDOException $e) {
            throw Error::fromPDO($e, $st->queryString);
        }

        return $success ? $st : false;
    }

    public function preparePDOParamsList(array | ParamsList $params): ParamsList
    {
        return ParamsList::prepare($params);
    }
}
