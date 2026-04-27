<?php

namespace Features\Pagination;

use JsonSerializable;

class PagesList implements JsonSerializable
{
    protected array $pages = [];
    protected int $limit = 1;
    protected int $current = 1;

    protected Pagination $pagination;

    public function __construct(Pagination $pagination)
    {
        $this->pagination = $pagination;

        $this->limit = $pagination->getLimit();
        $pagesCount = $pagination->getPagesCount();
        $this->current = ceil($pagination->getOffset() / $pagination->getLimit()) + 1;

        for ($page = 1; $page <= $pagesCount; $page++) {
            $offset = ($page - 1) * $pagination->getLimit();
            $pg = new Page($this->limit, $offset);
            if ($this->current === $page) {
                $pg->setCurrent(true);
            }
            $this->pages[] = $pg;
        }
    }

    public function getCurrentLimit(): int
    {
        return $this->limit;
    }

    public function getCurrentPageNum(): int
    {
        return $this->current;
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

    public function getList(): array
    {
        return $this->pages;
    }

    public function jsonSerialize(): mixed
    {
        return $this->pagination;
    }
}
