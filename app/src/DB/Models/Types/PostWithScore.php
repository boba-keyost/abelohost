<?php

namespace DB\Models\Types;

class PostWithScore extends Post
{
    public float $category_score;
    public float $keyword_score;
    public float $content_score;

    public function getScore(): int
    {
        return $this->category_score + $this->keyword_score + $this->content_score;
    }
}
