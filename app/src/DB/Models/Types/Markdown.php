<?php

namespace DB\Models\Types;

use FastVolt\Helper\Markdown as BaseMarkdown;
use Features\Functions;
use JsonSerializable;

class Markdown implements JsonSerializable
{
    protected ?BaseMarkdown $md = null;
    protected string $compiled = "";

    public function md(): BaseMarkdown
    {
        if (is_null($this->md)) {
            $this->md = new BaseMarkdown();
        }
        return $this->md;
    }

    public function jsonSerialize(): mixed
    {
        return $this->__toString();
    }

    public function getExcerpt(int $length = 300): string
    {
        return Functions::cutText($this, $length);
    }

    public function getHtml(): string
    {
        return $this->md()->getHtml();
    }

    public function __toString(): string
    {
        if (empty($this->compiled)) {
            $this->compiled = $this->md()->toHtml();
        }
        return $this->compiled;
    }
}
