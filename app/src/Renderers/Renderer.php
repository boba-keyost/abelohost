<?php

namespace Renderers;

use Extensions\WithConfig;
use Extensions\WithLogger;

interface Renderer extends WithLogger, WithConfig
{
    public const string RENDERER_DEFAULT = "default";
    public const string RENDERER_HTML = "html";
    public const string RENDERER_SCSS = "scss";
    public const string RENDERER_JSON = "json";

    public function render(mixed $data = null, array | null $parameters = null): void;
    public function renderError(mixed $data = null, int $code = 500, array | null $parameters = null): void;

    public function renderContent(mixed $data = null, array | null $parameters = null): void;
}
