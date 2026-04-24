<?php

namespace DB\Models\Types;

class Category
{
    use WithCreated;
    use WithSoftDelete;

    public int $id;
    public string $slug;
    public string $name;

    public function __construct()
    {
        $this->initCreatedFields();
        $this->initDeletedFields();
    }
}
