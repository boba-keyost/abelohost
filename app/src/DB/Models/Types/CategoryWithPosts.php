<?php

namespace DB\Models\Types;

class CategoryWithPosts extends Category
{
    public string|array $posts_ids;

    public function __construct()
    {
        parent::__construct();
        if (!empty($this->posts_ids)) {
            $this->posts_ids = array_map("intval", explode(',', $this->posts_ids));
        }
    }
}
