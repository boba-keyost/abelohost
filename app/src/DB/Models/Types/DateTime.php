<?php

namespace DB\Models\Types;

use DateTime as BaseDateTime;
use JsonSerializable;

class DateTime extends BaseDateTime implements JsonSerializable
{
    public function jsonSerialize(): mixed
    {
        return $this->format('c');
    }
}