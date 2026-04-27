<?php

namespace Features\SortFields;

class SortList
{
    protected array $fields = [];

    public function __construct(SortFields $sortFields)
    {
        $selected = [];
        foreach ($sortFields as $sortField) {
            $selected[] = $sortField->getField();
        }
        foreach ($sortFields->getAllowedSortFields() as $field) {
            $this->fields[] = $sortFields->getOrCreateField($field);
        }
    }

    public function getList(): array
    {
        return $this->fields;
    }
}
