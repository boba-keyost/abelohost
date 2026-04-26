<?php

namespace DB;

use PDO;
use PDOStatement;

class ParamsList
{
    protected array $paramsMap = [];
    protected array $paramsOrder = [];

    protected array $listParamsCount = [];


    public static function prepare(array | self $paramsList): static
    {
        return $paramsList instanceof static
            ? $paramsList
            : new static($paramsList);
    }

    protected function __construct(array $params)
    {
        foreach ($params as $key => $value) {
            $type = PDO::PARAM_STR;
            if (is_array($value) && count($value) === 2) {
                $type = $value['type'] ?? $value[1];
                $value = $value['value'] ?? $value[0];
            }
            $this->set(
                $key,
                $value,
                $type,
            );
        }
    }

    public function getListParamsCount(): array
    {
        return $this->listParamsCount;
    }

    public function hasListParamsCount(string $param): bool
    {
        return !empty($this->listParamsCount) && $this->listParamsCount[$param] > 0;
    }

    public function set(string | int $param, mixed $value, int $type = PDO::PARAM_STR): static
    {
        if (is_iterable($value)) {
            $this->listParamsCount[$param] = count($value);
            foreach ($value as $k => $v) {
                $this->setParam(
                    is_numeric($param) ? $param + $k : $param . "_" . $k,
                    $v,
                    $type,
                );
            }
        } else {
            $this->setParam(
                $param,
                $value,
                $type,
            );
        }

        return $this;
    }

    protected function setParam(string | int $param, mixed $value, int $type = PDO::PARAM_STR): static
    {
        $this->paramsMap[$param] = [$value, $type];
        if (!$this->has($param)) {
            $this->paramsOrder[] = $param;
        }

        return $this;
    }

    public function has(string | int $param): bool
    {
        return in_array($param, $this->paramsOrder, true);
    }

    public function merge(ParamsList $newParams): void
    {
        foreach ($newParams->getParamMap() as $name => [$param, $type]) {
            $this->set($name, $param, $type);
        }
    }

    public function getParam(string | int $param): array
    {
        return $this->paramsMap[$param] ?? [];
    }

    public function getParamNames(): array
    {
        return $this->paramsOrder;
    }

    public function getParamMap(): array
    {
        return $this->paramsMap;
    }

    public function getParamList(): array
    {
        $pl = array_map(
            fn ($p) => [is_numeric($p) ? $p : ":" . $p, ...$this->getParam($p)],
            $this->paramsOrder,
        );
        return $pl;
    }

    public function bindTo(PDOStatement $st): void
    {
        foreach ($this->getParamList() as $bindArgs) {
            call_user_func_array([$st, "bindValue"], $bindArgs);
        }
    }
}
