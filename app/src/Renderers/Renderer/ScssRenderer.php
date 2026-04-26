<?php

namespace Renderers\Renderer;

use Renderers\BaseRenderer;
use Throwable;

class ScssRenderer extends BaseRenderer
{
    public static function renderData(mixed $data = null): void
    {
        if ($data instanceof Throwable) {
            $content = "/** Error: " . $data->getMessage() . " */";
        } else {
            if (!empty($data['asset'])) {
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s T', $data['asset']->getMTime()));
                header('Cache-Control: max-age=432000');
            }
            $content = $data['css'];
        }
        header("Content-type: text/css");
        parent::renderData($content);
    }
}
