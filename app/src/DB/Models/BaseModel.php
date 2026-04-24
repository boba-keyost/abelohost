<?php

namespace DB\Models;

use DB\DB;
use DB\ParamsList;
use Extensions\DBExtension;
use PDO;
use stdClass;

abstract class BaseModel implements Model
{
    use DBExtension;

    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    protected function queryWithLimit(string $query, int $limit = -1, int $offset = 0): string
    {
        if ($limit >= 0) {
            $query .= " LIMIT :limit";
        }
        if ($offset > 0) {
            $query .= " OFFSET :offset";
        }
        return $query;
    }

    protected function paramsWithLimit(array | ParamsList $params = [], int $limit = -1, int $offset = 0): ParamsList
    {
        $params = ParamsList::prepare($params);
        if ($limit >= 0) {
            $params->set('limit', $limit, PDO::PARAM_INT);
        }
        if ($offset > 0) {
            $params->set('offset', $offset, PDO::PARAM_INT);
        }
        return $params;
    }

    protected function queryWithList(string $query, array | ParamsList $params = []): string
    {
        $params = ParamsList::prepare($params);
        $listParams = $params->getListParamsCount();
        foreach ($listParams as $param => $count) {
            if (is_int($param)) {
                /*
                 * todo: implement for numeric parameters
                */
            } else {
                if ($count > 0) {
                    $paramVars = [];
                    for ($i = 0; $i < $count; $i++) {
                        $paramVars[] = ":" . $param . "_" . $i;
                    }
                    $repl = implode(", ", $paramVars);
                } else {
                    $repl = "NULL";
                }
                $query = str_replace(":" . $param, $repl, $query);
            }
        }

        return $query;
    }

    protected function paramsWithList(array | ParamsList $params = []): ParamsList
    {
        return ParamsList::prepare($params);
    }

    protected function getValue(string $query, array | ParamsList $params = []): string | int
    {
        $st = $this->getDb()->execute($query, $params);
        $col = $st->fetch(PDO::FETCH_COLUMN);

        return $col[0];
    }

    protected function getRows(string $query, array | ParamsList $params = []): array
    {
        $rows = [];
        $st = $this->getDb()->execute($query, $params);
        while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = $row;
        }

        return $rows;
    }

    protected function getObject(string $query, array | ParamsList $params = [], string $class = stdClass::class): array
    {
        $st = $this->getDb()->execute($query, $params);
        $st->setFetchMode(PDO::FETCH_CLASS, $class);
        return $st->fetch();
    }

    protected function getRowsObject(
        string $query,
        array | ParamsList $params = [],
        string $class = stdClass::class
    ): array {
        $rows = [];
        $st = $this->getDb()->execute($query, $params);
        $st->setFetchMode(PDO::FETCH_CLASS, $class);
        while ($row = $st->fetch()) {
            $rows[] = $row;
        }

        return $rows;
    }

    protected function getColumn(string $query, array | ParamsList $params = []): array
    {
        $values = [];
        $st = $this->getDb()->execute($query, $params);
        while ($val = $st->fetch(PDO::FETCH_COLUMN)) {
            $values[] = $val;
        }

        return $values;
    }
}
