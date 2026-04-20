<?php

namespace Router;

use Exception;
use Extensions\LoggerExtension;
use Renderers\Renderer;
use Renderers\RendererFabric;

class BaseRoute extends LoggerExtension implements Route {
    protected ?Renderer $renderer;
    protected array $parameters;

    /**
     * @throws Exception
     */
    public function run(array $parameters = [], mixed $body = null): void
    {
        $this->getRenderer()->render("default route");
    }

    /**
     * @throws Exception
     */
    public function respond(mixed $data): void {
        try {
            $this->getRenderer()->render($data, $this->parameters["rendererParameters"] ?? null);
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function error(mixed $message, int $code = 500): void {
        $this->getLogger()->error($message);
        $this->getRenderer()->renderError($message, $code, $this->parameters["rendererParameters"] ?? null);
    }

    public function setParameters(array $parameters): static{
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
    public function getRenderer(): Renderer {
        if (is_null($this->renderer)) {
            $this->renderer = RendererFabric::get();
        }
        return $this->renderer;
    }
}