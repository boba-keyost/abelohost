<?php

namespace DB\Models\Types;

use JsonSerializable;
use stdClass;

class BaseType implements JsonSerializable
{
    public function __toString(): string
    {
        return json_encode($this, JSON_PRETTY_PRINT);
    }

    public function jsonSerialize(): mixed
    {
        $ref = new \ReflectionClass($this);
        $params = new stdClass();
        foreach ($ref->getProperties() as $property) {
            if ($property->isPublic() && $property->isInitialized($this)) {
                $params->{$property->getName()} = $property->getValue($this);
            }
        }
        return $params;
    }
}
