<?php

namespace Renderers;

class ScssRenderer extends BaseRenderer
{
    public function render(mixed $data = null, array | null $parameters = null): void
    {
        if (!empty($data['stat'])) {
            $mtime = $data['stat']['mtime'];
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s T', $mtime));
            header('Cache-Control: max-age=432000');
        }
        header("Content-type: text/css");
        parent::render($data['css']);
    }
}
