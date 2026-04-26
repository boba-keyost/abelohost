<?php

namespace Renderers;

use Throwable;

class Error extends \App\Error
{
    protected const string PREFIX = "Renderer";

    protected ?Renderer $renderer = null;

    public function getRenderer(): ?Renderer
    {
        return $this->renderer;
    }

    public function setRenderer(?Renderer $renderer): static
    {
        $this->renderer = $renderer;
        return $this;
    }

    public static function fromRenderer(
        ?Renderer $renderer = null,
        mixed $e = null,
        int $code = 0,
        ?Throwable $previous = null
    ): static {
        $e = static::fromError($e, $code, $previous);
        $message = $e->getMessage();
        if (!empty($renderer)) {
            $e->message .= " (renderer: " . get_class($renderer) . ")";
        }

        return $e;
    }
}
