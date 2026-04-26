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
            $this->fields[] = new SortField($field)
                ->setCurrent(in_array($field, $selected));
        }
    }

    public function getList(): array
    {
        return $this->fields;
    }
}
