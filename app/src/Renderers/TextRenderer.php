<?php

namespace Renderers;

class TextRenderer extends BaseRenderer {
    public function render(mixed $data = null, array | null $parameters = null): void {
        header("Content-type: text/plain");
        parent::render($data);
    }
}