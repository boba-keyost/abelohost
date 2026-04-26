<?php

namespace Renderers\Renderer;

use Renderers\BaseRenderer;

class TextRenderer extends BaseRenderer
{
    public static function renderData(mixed $data = null): void
    {
        header("Content-type: text/plain");
        parent::renderData($data);
    }
}
