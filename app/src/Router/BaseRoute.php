<?php

namespace Router;

use Exception;
use Extensions\DBExtension;
use Extensions\LoggerExtension;
use Renderers\Renderer;
use Renderers\RendererFabric;
use Throwable;

abstract class BaseRoute implements Route
{
    use LoggerExtension;
    use DBExtension;

    protected ?Renderer $renderer;
    protected array $parameters;

    /**
     * @throws Exception
     */
    public function run(array $parameters = [], mixed $body = null): void
    {
        try {
            $resp = $this->handle($parameters, $body);
            if ($resp instanceof Throwable) {
                $this->error($resp);
            } else {
                $this->respond($resp);
            }
        } catch (Throwable $e) {
            $this->error($e);
        }
    }

    /**
     * @throws Exception
     */
    public function respond(mixed $data): void
    {
        try {
            $this->getRenderer()->render($data, $this->parameters["rendererParameters"] ?? null);
        } catch (Throwable $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function error(mixed $message, int $code = 500): void
    {
        $this->getLogger()->error($message);
        if ($message instanceof Error) {
            $code = $message->getCode();
        }
        $this->getRenderer()->renderError($message, $code, $this->parameters["rendererParameters"] ?? null);
    }

    public function setParameters(array $parameters): static
    {
        $this->parameters = $parameters;
        return $this;
    }

    public function setRenderer(Renderer $renderer): static
    {
        $this->renderer = $renderer;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function getRenderer(): Renderer
    {
        if (is_null($this->renderer)) {
            $this->renderer = RendererFabric::get();
        }
        return $this->renderer;
    }
}
