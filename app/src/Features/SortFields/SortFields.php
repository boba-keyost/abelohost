<?php

namespace Features\SortFields;

use Countable;
use Iterator;
use JsonSerializable;

class SortFields implements Iterator, Countable, JsonSerializable
{
    protected array $allowedFields = [];

    protected array $registeredFields = [];

    protected array $list = [];
    protected int $i = 0;

    protected bool $checkUnknown = true;

    public function __construct(array $allowedFields = [], array $defaultFields = [])
    {
        $this->allowedFields = $allowedFields;
        $this->setFields($defaultFields, true);
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

    public function checkFieldAllowed(?string $field = null): bool
    {
        if ($field && in_array($field, $this->getAllowedSortFields())) {
            return true;
        } elseif ($field && $this->getCheckUnknown()) {
            throw new Error("Unknown field '$field'.");
        } else {
            return false;
        }
    }

    public function getAllowedSortFields(): array
    {
        return $this->allowedFields;
    }

    public function setAllowedFields(array $allowedFields): static
    {
        $this->allowedFields = $allowedFields;
        return $this;
    }

    public function setFields(mixed $fields, bool $isDefaultFields = false, bool $reset = true): static
    {
        if (!$isDefaultFields) {
            /** @var SortField $field */
            foreach ($this->registeredFields as $field) {
                $field->setCurrent(false);
                $field->resetDefaultOrder();
                $field->setOrder("asc");
            }
        }
        $fields = $this->prepare($fields, $isDefaultFields);
        if ($reset) {
            $this->list = [];
        }
        /** @var SortField $field */
        foreach ($fields as $field) {
            $k = $field->getField();
            $field->setCurrent(true);
            if ($isDefaultFields) {
                $field->setDefaultOrder($field->getOrder());
            } else {
                $field->setTouched(true);
            }
            $this->list[] = $k;
            $this->registeredFields[$k] = $field;
        }
        return $this;
    }

    public function getField(string $field): ?SortField
    {
        return $this->checkFieldAllowed($field) ? $this->registeredFields[$field] ?? null : null;
    }

    public function getOrCreateField(string $field): ?SortField
    {
        $fld = $this->getField($field);
        if (is_null($fld)) {
            $fld = new SortField($field);
        }

        return $fld;
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

                if ($this->checkFieldAllowed($field)) {
                    $fld = $this->getOrCreateField($field)
                        ->setOrder($order);

                    $fields[] = $fld;
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
        return $this->getField($this->list[$this->i]);
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
        return $this->i < $this->count();
    }

    public function rewind(): void
    {
        $this->i = 0;
    }

    public function count(): int
    {
        return count($this->list);
    }

    public function toArray(): array
    {
        return array_reduce(
            $this->list,
            function (array $fields, string $field): array {
                $fields[] = $this->getField($field);
                return $fields;
            },
            []
        );
    }

    public function __toString(): string
    {
        return implode(', ', $this->toArray());
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
