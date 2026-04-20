<?php

namespace Renderers;

class ScssRenderer extends BaseRenderer {
    public function render(mixed $data = null, array | null $parameters = null): void {
        header("Content-type: text/css");
        parent::render($data);
    }
}