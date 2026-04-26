<?php

namespace Renderers;

use Extensions\ConfigExtension;
use Extensions\LoggerExtension;
use Throwable;

abstract class BaseRenderer implements Renderer
{
    use LoggerExtension;
    use ConfigExtension;

    public function render(mixed $data = null, array | null $parameters = null): void
    {
        try {
            $this->renderContent($data, $parameters);
        } catch (Throwable $e) {
            $err = Error::fromRenderer($this, $e, 0);
            $this->getLogger()->error($err->getMessage());
            $this->renderError($err);
        }
    }
    public function renderError(mixed $data = null, int $code = 500, array | null $parameters = null): void
    {
        header("HTTP/1.1 " . $code . " error");
        $this->renderContent($data, $parameters);
    }
    public function renderContent(mixed $data = null, array | null $parameters = null): void
    {
        static::renderData($data);
    }

    public static function renderData(mixed $data = null): void
    {
        echo is_scalar($data) ? $data : json_encode($data);
    }
}
