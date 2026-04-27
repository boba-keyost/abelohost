<?php

namespace Features\SortFields;

class SortField
{
    protected string $field = "";
    protected string $order = "ASC";
    protected ?string $defaultOrder = null;
    protected bool $touched = false;
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

    public function getDefaultOrder(): ?string
    {
        return $this->defaultOrder;
    }

    public function getQueryOrder(): string | null
    {
        $order = strtolower($this->getOrder());
        $defaultOrder = strtolower($this->getDefaultOrder() ?? "");
        if ($this->isCurrent()) {
            $shouldReset = $defaultOrder ? $order === $defaultOrder : $order === "desc";
            if ($this->isTouched() && $shouldReset) {
                $order = null;
            } else {
                $order = $order === "asc" ? "desc" : "asc";
            }
        }

        return $order;
    }

    public function isCurrent(): bool
    {
        return (bool) $this->current;
    }

    public function isTouched(): bool
    {
        return (bool) $this->touched;
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

    public function setDefaultOrder(string $defaultOrder): static
    {
        $defaultOrder = strtoupper($defaultOrder);
        if ($defaultOrder === "ASC" || $defaultOrder === "DESC") {
            $this->defaultOrder = $defaultOrder;
        }
        return $this;
    }

    public function resetDefaultOrder(): static
    {
        $this->defaultOrder = "";

        return $this;
    }

    public function setTouched(bool $touched): static
    {
        $this->touched = $touched;
        return $this;
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

    public function queryParam(string $param, $queryParams = [], bool $append = true): string
    {
        if (empty($queryParams)) {
            $queryParams = [];
        }
        if (!$append || empty($queryParams[$param])) {
            $queryParams[$param] = [];
        }

        $paramsList = $append ? $queryParams[$param] ?? [] : [];

        $qParam = [
            "field" => $this->getField(),
            "order" => $this->getQueryOrder(),
        ];

        $ind = 0;
        if ($append) {
            $ind = -1;
            foreach ($paramsList as $i => $q) {
                if (!empty($q['field']) && $q['field'] === $qParam["field"]) {
                    $ind = $i;
                    break;
                }
            }
            if ($ind < 0) {
                $ind = count($paramsList);
            }
        }
        if (!is_null($qParam["order"])) {
            $paramsList[$ind] = $qParam;
        } elseif ($ind < count($paramsList)) {
            array_splice($paramsList, $ind, 1);
        }

        $queryParams[$param] = $paramsList;

        return http_build_query($queryParams);
    }
}
