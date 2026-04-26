<?php

namespace Features\SortFields;

class SortField
{
    protected string $field = "";
    protected string $order = "ASC";
    protected bool $current = false;

    public function __construct(string $field, ?string $order = null)
    {
        $this->setField($field);
        if ($order) {
            $this->setOrder($order);
        }
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getOrder(): string
    {
        return $this->order;
    }

    public function setField(string $field): static
    {
        $this->field = trim($field);
        return $this;
    }

    public function setOrder(string $order): static
    {
        $order = strtoupper($order);
        if ($order === "ASC" || $order === "DESC") {
            $this->order = $order;
        }
        return $this;
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
        return sprintf(
            "%s %s",
            $this->field,
            strtoupper($this->order)
        );
    }

    public function queryParam(string $param, $queryParams = [], bool $append = false): string
    {
        if (empty($queryParams)) {
            $queryParams = [];
        }
        if (!$append || empty($queryParams[$param])) {
            $queryParams[$param] = [];
        }
        $queryParams[$param][] = [
            "field" => $this->getField(),
            "order" => strtolower($this->getOrder()),
        ];

        return http_build_query($queryParams);
    }
}
