<?php

namespace Renderers;

interface Renderer {

    const string RENDERER_DEFAULT = "default";
    const string RENDERER_HTML = "html";
    const string RENDERER_SCSS = "scss";
    const string RENDERER_JSON = "json";

    public function render(mixed $data = null, array | null $parameters = null): void;
    public function renderError(mixed $data = null, int $code = 500, array | null $parameters = null): void;
}