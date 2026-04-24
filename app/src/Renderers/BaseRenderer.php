<?php

namespace Renderers;

use Extensions\LoggerExtension;
use Throwable;

abstract class BaseRenderer implements Renderer
{
    use LoggerExtension;

    public function render(mixed $data = null, array | null $parameters = null): void
    {
        echo is_scalar($data) ? "" . $data : json_encode($data, JSON_PRETTY_PRINT);
    }
    public function renderError(mixed $data = null, int $code = 500, array | null $parameters = null): void
    {
        header("HTTP/1.1 " . $code . " error");
        if ($data instanceof Throwable) {
            $data = [
                "message" => $data->getMessage(),
                "file" => sprintf("%s:%s", $data->getFile(), $data->getLine()),
                "stacktrace" => $data->getTrace(),
            ];
        }
        $this->render(["error" => $data]);
    }
}
