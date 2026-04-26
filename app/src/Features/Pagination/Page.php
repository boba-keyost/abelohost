<?php

namespace Features\Pagination;

class Page
{
    protected ?int $limit = null;
    protected ?int $offset = null;
    protected bool $current = false;

    public function __construct(?int $limit = null, ?int $offset = null)
    {
        $this->limit = $limit;
        $this->offset = $offset;
    }

    public function getNum(): int
    {
        return ceil($this->offset / $this->limit) + 1;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function isCurrent(): bool
    {
        return (bool) $this->current;
    }

    public function setCurrent(bool $current): static
    {
        $this->current = $current;
        return $this;
    }

    public function __toString(): string
    {
        return $this->queryParam();
    }

    public function queryParam($queryParams = []): string
    {
        if (empty($queryParams)) {
            $queryParams = [];
        }
        $queryParams['limit'] = $this->limit;
        $queryParams['offset'] = $this->offset;

        return http_build_query($queryParams);
    }
}
