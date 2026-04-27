<?php

namespace Renderers\Renderer;

use Renderers\BaseRenderer;

class JsonRenderer extends BaseRenderer
{
    public function renderContent(mixed $data = null, array | null $parameters = null): void
    {
        $flags = 0;
        if (!empty($parameters["prettyPrint"])) {
            $flags = JSON_PRETTY_PRINT;
        }

        parent::renderContent(json_encode($data, $flags));
    }

    public static function renderData(mixed $data = null): void
    {
        header("Content-type: application/json");
        echo is_scalar($data) ? $data : json_encode($data);
    }
}
