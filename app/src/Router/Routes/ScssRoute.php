<?php

namespace Router\Routes;

use Renderers\Renderer;
use Renderers\RendererType;
use Router\BaseRoute;
use Router\RouteAttribute;

use ScssPhp\ScssPhp\CompilationResult;
use ScssPhp\ScssPhp\Compiler;

#[RouteAttribute("GET", "/assets/styles/{file}.scss", ["renderer" => RendererType::Scss])]
class ScssRoute extends BaseRoute {
    public function run(array $parameters = [], mixed $body = null): void
    {
        $assetsDir = realpath(__DIR__ . "/../../../assets/styles");
        $filePath = $assetsDir . "/" . $parameters["file"] . '.scss';
        if (file_exists($filePath)) {
            $compiler = new Compiler();
            $compiler->setSourceMap(Compiler::SOURCE_MAP_FILE);
            // todo:implement  $compiler->setLogger($this->getLogger());

            $this->respond($compiler->compileFile($filePath));
        } else {
            $this->error("File $filePath does not exist", 404);
        }
    }

    public function respond(mixed $result): void
    {
        if ($result instanceof CompilationResult) {
            $css = $result->getCss();
            $map = $result->getSourceMap();
            if ($map) {
                $css .= "\n\n//# sourceMappingURL=data:application/json;charset=utf-8;base64," . base64_encode($map);
            }
            parent::respond($css);
        } else {
            parent::respond($result);
        }
    }
}