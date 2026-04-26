<?php

namespace DB\Models;

use DB\DB;
use DB\ParamsList;
use DB\Rows;
use Extensions\DBExtension;
use Features\SortFields\SortFields;
use PDO;
use stdClass;

abstract class BaseModel implements Model
{
    use DBExtension;

    protected const string PARAM_LIMIT = "limit";
    protected const string PARAM_OFFSET = "offset";

    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    protected function queryWithLimit(string $query, int $limit = -1, int $offset = 0): string
    {
        if ($limit >= 0) {
            $query .= " LIMIT :" . static::PARAM_LIMIT;
        }
        if ($offset > 0) {
            $query .= " OFFSET :" . static::PARAM_OFFSET;
        }
        return $query;
    }

    protected function paramsWithLimit(array | ParamsList $params = [], int $limit = -1, int $offset = 0): ParamsList
    {
        $params = ParamsList::prepare($params);
        if ($limit >= 0) {
            $params->set(static::PARAM_LIMIT, $limit, PDO::PARAM_INT);
        }
        if ($offset > 0) {
            $params->set(static::PARAM_OFFSET, $offset, PDO::PARAM_INT);
        }
        return $params;
    }

    protected function queryWithSorting(
        string $query,
        iterable $sortFields = [],
        array | ParamsList $params = []
    ): string {
        $sortFields = $this->prepareSortField($sortFields);
        ParamsList::prepare($params);

        if ($sortFields->count() > 0) {
            $query .= " ORDER BY " . $sortFields;
        }

        return $query;
    }

    protected function paramsWithSorting(array | ParamsList $params = [], iterable $sortFields = []): ParamsList
    {
        return ParamsList::prepare($params);
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

    public function getAllowedSortFields(): array
    {
        return [];
    }

    public function getDefaultSortFields(): array
    {
        return [];
    }

    public function prepareSortField(
        SortFields | array | null $sortFields = null,
        array $allowedFields = [],
        array $defaultFields = []
    ): SortFields {
        return SortFields::init(
            $sortFields,
            !empty($allowedFields) ? $allowedFields : $this->getAllowedSortFields(),
            !empty($defaultFields) ? $defaultFields : $this->getDefaultSortFields(),
        );
    }

    protected function getValue(string $query, array | ParamsList $params = []): string | int
    {
        $st = $this->getDb()->execute($query, $params);
        $col = $st->fetch(PDO::FETCH_COLUMN);

        return $col[0];
    }

    protected function getRows(string $query, array | ParamsList $params = []): Rows
    {
        $st = $this->getDb()->execute($query, $params);

        $st->setFetchMode(PDO::FETCH_ASSOC);
        return new Rows($st);
    }

    protected function getObject(
        string $query,
        array | ParamsList $params = [],
        string $class = stdClass::class
    ): object | false {
        $st = $this->getDb()->execute($query, $params);
        $st->setFetchMode(PDO::FETCH_CLASS, $class);
        return $st->fetch();
    }

    protected function getRowsObject(
        string $query,
        array | ParamsList $params = [],
        string $class = stdClass::class
    ): Rows {
        $st = $this->getDb()->execute($query, $params);
        $st->setFetchMode(PDO::FETCH_CLASS, $class);
        return new Rows($st);
    }

    protected function getColumn(string $query, int $index = 0, array | ParamsList $params = []): Rows
    {
        $st = $this->getDb()->execute($query, $params);
        $st->setFetchMode(PDO::FETCH_COLUMN, $index);
        return new Rows($st);
    }
}
