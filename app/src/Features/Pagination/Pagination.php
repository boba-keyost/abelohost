<?php

namespace Features\Pagination;

class Pagination
{
    protected int $limit = 10;
    protected int $offset = 0;
    protected int $total = 0;
    protected int $count = 0;

    public function setParam(string $key, int $value): static
    {
        return match ($key) {
            "limit" => $this->setLimit($value),
            "offset" => $this->setOffset($value),
            "total" => $this->setTotal($value),
            "count" => $this->setCount($value),
            default => $this,
        };
    }

    public function setLimit(int $limit = 10): static
    {
        if ($limit > 0) {
            $this->limit = $limit;
        }
        return $this;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setOffset(int $offset): static
    {
        if ($offset >= 0) {
            $this->offset = $offset;
        }
        return $this;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function setTotal(int $total): static
    {
        if ($total > 0) {
            $this->total = $total;
        }
        return $this;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setCount(int $count): static
    {
        if ($count > 0) {
            $this->count = $count;
        }
        return $this;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getPagesCount(): int
    {
        return ceil($this->getTotal() / $this->getLimit());
    }

    public function getPagesList(): PagesList
    {
        return new PagesList($this);
    }

    public static function init(self | array | null $params = null): static
    {
        if ($params instanceof static) {
            return $params;
        } else {
            $pagination = !empty($params["pagination"]) && $params["pagination"] instanceof static
                ? $params["pagination"]
                : new static();
            if (!empty($params)) {
                foreach (["limit", "offset"] as $key) {
                    if (array_key_exists($key, $params)) {
                        $pagination->setParam($key, intval($params[$key]));
                    }
                }
            }

            return $pagination;
        }
    }

    public function queryParam($queryParams = []): string
    {
        if (empty($queryParams)) {
            $queryParams = [];
        }
        unset($queryParams['limit']);
        unset($queryParams['offset']);

        return http_build_query($queryParams);
    }
}
