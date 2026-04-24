<?php

namespace Renderers;

use Exception;

class RendererFabric
{
    protected static array $renderers = [];

    /**
     * @throws Exception
     */
    public static function get(RendererType $type = RendererType::Default): Renderer
    {
        $k = $type->name;
        if (!array_key_exists($k, static::$renderers)) {
            static::$renderers[$k] = match ($type) {
                RendererType::Default => new TextRenderer(),
                RendererType::Html => new HtmlRenderer(),
                RendererType::Scss => new ScssRenderer(),
                RendererType::Json => new JsonRenderer(),
                default => throw new Exception("Unknown renderer type $k"),
            };
        }

        return static::$renderers[$k];
    }
}
