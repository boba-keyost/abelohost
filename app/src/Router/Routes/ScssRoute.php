<?php

namespace Router\Routes;

use Renderers\RendererType;
use Router\BaseRoute;
use Router\Error;
use Router\RouteAttribute;
use ScssPhp\ScssPhp\Ast\Sass\Statement\Stylesheet;
use ScssPhp\ScssPhp\Ast\Sass\Statement\VariableDeclaration;
use ScssPhp\ScssPhp\CompilationResult;
use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\Syntax;
use ScssPhp\ScssPhp\ValueConverter;

#[RouteAttribute("GET", "/assets/styles/{file}.scss", ["renderer" => RendererType::Scss])]
class ScssRoute extends BaseRoute
{
    protected array $assetsCache = [];

    /**
     * @throws Error
     */
    public function getAssetContent(string $file): array
    {
        if (empty($this->assetsCache[$file])) {
            $assetsDir = realpath(__DIR__ . "/../../../assets/styles");
            $filePath = $assetsDir . "/" . $file . '.scss';
            if (file_exists($filePath)) {
                $this->assetsCache[$file] = [
                    'filepath' => $filePath,
                    'content' => file_get_contents($filePath),
                    'stat' => stat($filePath),
                ];
            } else {
                throw Error::notFound("File $filePath does not exist");
            }
        }

        return $this->assetsCache[$file];
    }

    public function getVariables(): array
    {
        $variables = [];
        [
            'content' => $varsContent,
        ] = $this->getAssetContent("variables");

        $res = Stylesheet::parse($varsContent, Syntax::SCSS, null, null);
        foreach ($res->getChildren() as $child) {
            if ($child instanceof VariableDeclaration) {
                $expr = $child->getExpression();
                $variables[$child->getName()] = $expr->__toString();
            }
        }

        return $variables;
    }

    public function handle(array $parameters = [], mixed $body = null): mixed
    {
        $isVars = $parameters["file"] === "variables";
        [
            'content' => $content,
            'filepath' => $filepath,
            'stat' => $stat,
        ] = $this->getAssetContent($parameters["file"]);

        $variables = $this->getVariables();

        if ($isVars) {
            $data = sprintf(
                ":root{\n    %s\n}",
                implode(
                    "\n    ",
                    array_map(
                        fn ($var, $val) => sprintf("--%s: %s;", $var, $val),
                        array_keys($variables),
                        array_values($variables)
                    ),
                )
            );
        } else {
            $compiler = new Compiler();
            $compiler->setSourceMap(Compiler::SOURCE_MAP_FILE);

            $vars = [];
            foreach (array_keys($variables) as $name) {
                $vars[$name] = ValueConverter::parseValue("var(--" . $name . ")");
            }
            $compiler->replaceVariables($vars);

            $data = $compiler->compileString($content, $filepath);
        }

        return ['css' => $data, 'stat' => $stat];
    }

    public function respond(mixed $data): void
    {
        $stat = null;
        if (is_array($data)) {
            [
                'css' => $data,
                'stat' => $stat,
            ] = $data;
        }

        if ($data instanceof CompilationResult) {
            $css = $data->getCss();
            $map = $data->getSourceMap();
            if ($map) {
                $css .= "\n\n//# sourceMappingURL=data:application/json;charset=utf-8;base64," . base64_encode($map);
            }
        } else {
            $css = $data;
        }
        parent::respond(['css' => $css, 'stat' => $stat]);
    }
}
