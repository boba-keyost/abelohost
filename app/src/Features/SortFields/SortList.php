<?php

namespace Features\SortFields;

use JsonSerializable;

class SortList implements JsonSerializable
{
    protected array $fields = [];

    protected SortFields $sortFields;

    public function __construct(SortFields $sortFields)
    {
        $this->sortFields = $sortFields;
        foreach ($sortFields->getAllowedSortFields() as $field) {
            $this->fields[] = $sortFields->getOrCreateField($field);
        }
    }

    public function getList(): array
    {
        return $this->fields;
    }

    public function jsonSerialize(): mixed
    {
        return $this->sortFields;
    }
}
