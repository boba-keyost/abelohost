<?php

namespace Renderers;

class JsonRenderer extends BaseRenderer
{
    public function render(mixed $data = null, array | null $parameters = null): void
    {
        header("Content-type: application/json");
        $flags = 0;
        if (!empty($parameters["prettyPrint"])) {
            $flags = JSON_PRETTY_PRINT;
        }
        parent::render(json_encode($data, $flags));
    }
}
