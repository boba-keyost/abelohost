<?php

namespace Features\SortFields;

use Countable;
use Iterator;

class SortFields implements Iterator, Countable
{
    protected array $allowedFields = [];
    protected array $defaultFields = [];
    protected array $fields = [];
    protected int $i = 0;

    protected bool $checkUnknown = true;

    public function __construct(array $allowedFields = [], array $defaultFields = [])
    {
        $this->allowedFields = $allowedFields;
        $this->defaultFields = $this->prepare($defaultFields);
    }

    public function getCheckUnknown(): bool
    {
        return $this->checkUnknown;
    }

    public function setCheckUnknown(bool $checkUnknown = true): self
    {
        $this->checkUnknown = $checkUnknown;
        return $this;
    }

    public static function init(
        self | array | null $params,
        array $allowedFields = [],
        array $defaultFields = []
    ): static {
        if ($params instanceof static) {
            return $params;
        } else {
            $sortFields = !empty($params["sort"]) && $params["sort"] instanceof static
                ? $params["sort"]
                : new static($allowedFields, $defaultFields);
            if (!empty($params["sort"])) {
                $sortFields->setFields($params["sort"]);
            }

            return $sortFields;
        }
    }

    public function getAllowedSortFields(): array
    {
        return $this->allowedFields;
    }

    public function getFields(): array
    {
        return empty($this->fields) ? $this->defaultFields : $this->fields;
    }

    public function setAllowedFields(array $allowedFields): static
    {
        $this->allowedFields = $allowedFields;
        return $this;
    }

    public function setFields(mixed $fields): static
    {
        $this->fields = $this->prepare($fields);

        return $this;
    }

    public function add($fields): static
    {
        $this->fields = array_merge($this->fields, $this->prepare($fields));
        return $this;
    }

    /**
     * @throws Error
     */
    protected function prepare(mixed $fields): array
    {
        if (!is_array($fields)) {
            $fields = [['field' => $fields]];
        }
        if (!empty($fields['field'])) {
            $fields = [$fields];
        }

        return array_reduce(
            $fields,
            function (array $fields, mixed $sortField): array {
                $field = null;
                $order = null;
                if (is_string($sortField)) {
                    $field = $sortField;
                } elseif (is_array($sortField)) {
                    if (array_key_exists('field', $sortField)) {
                        $field = $sortField['field'];
                    } elseif (count($sortField) > 0) {
                        $field = $sortField[0] ?? null;
                    }
                    if (array_key_exists('order', $sortField)) {
                        $order = $sortField['order'] ?? null;
                    } elseif (count($sortField) > 1) {
                        $order = $sortField[1] ?? null;
                    }
                }

                if ($field && in_array($field, $this->getAllowedSortFields())) {
                    $fields[] = new SortField($field, $order);
                } elseif ($field && $this->getCheckUnknown()) {
                    throw new Error("Unknown field '$field'.");
                }

                return $fields;
            },
            []
        );
    }


    public function getSortList(): SortList
    {
        return new SortList($this);
    }

    public function current(): mixed
    {
        return $this->fields[$this->i];
    }

    public function next(): void
    {
        $this->i++;
    }

    public function key(): mixed
    {
        return $this->i;
    }

    public function valid(): bool
    {
        return $this->i < count($this->fields);
    }

    public function rewind(): void
    {
        $this->i = 0;
    }

    public function count(): int
    {
        return count($this->fields);
    }

    public function __toString(): string
    {
        return implode(', ', $this->fields);
    }
}
