<?php

namespace Renderers;

use Extensions\LoggerExtension;

class BaseRenderer extends LoggerExtension implements Renderer{
    public function render(mixed $data = null, array | null $parameters = null): void
    {
        echo is_scalar($data) ? "" . $data : json_encode($data, JSON_PRETTY_PRINT);
    }
    public function renderError(mixed $data = null, int $code = 500, array | null $parameters = null): void
    {
        header("HTTP/1.1 " . $code . " error");
        $this->render(["error" => $data]);
    }
}