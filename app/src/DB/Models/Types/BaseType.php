<?php

namespace DB\Models\Types;

use stdClass;

class BaseType
{
    public function __toString(): string
    {
        return $this->toJson(JSON_PRETTY_PRINT);
    }

    public function toJson(int $options = 0): string
    {
        $ref = new \ReflectionClass($this);
        $params = new stdClass();
        foreach ($ref->getProperties() as $property) {
            if ($property->isPublic()) {
                $params->{$property->getName()} = $property->getValue($this);
            }
        }
        return json_encode($params, $options);
    }
}
