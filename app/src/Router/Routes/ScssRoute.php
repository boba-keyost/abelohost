<?php

namespace Router\Routes;

use App\Error;
use Features\Assets\Assets;
use Renderers\RendererType;
use Router\BaseRoute;
use Router\RouteAttribute;
use ScssPhp\ScssPhp\Ast\Sass\Expression\VariableExpression;
use ScssPhp\ScssPhp\Ast\Sass\Statement\Stylesheet;
use ScssPhp\ScssPhp\Ast\Sass\Statement\VariableDeclaration;
use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\Exception\SassException;
use ScssPhp\ScssPhp\Exception\SassFormatException;
use ScssPhp\ScssPhp\Syntax;
use ScssPhp\ScssPhp\ValueConverter;

#[RouteAttribute("GET", "/assets/styles/{file}.scss", ["renderer" => RendererType::Scss])]
class ScssRoute extends BaseRoute
{
    /**
     * @throws SassFormatException
     * @throws Error
     */
    public function getVariables(): array
    {
        $variables = [];
        $varAsset = Assets::getInstance()->loadSCSSAsset("variables");

        $res = Stylesheet::parse($varAsset->getContent(), Syntax::SCSS, null, null);
        foreach ($res->getChildren() as $child) {
            if ($child instanceof VariableDeclaration) {
                $expr = $child->getExpression();
                $variables[$child->getName()] = match (true) {
                    $expr instanceof VariableExpression => "var(--" . $expr->getName() . ")",
                    default => $expr->__toString()
                };
            }
        }

        return $variables;
    }

    /**
     * @throws SassFormatException
     * @throws Error
     * @throws SassException
     */
    public function handle(array $parameters = [], mixed $body = null): mixed
    {
        $isVars = $parameters["file"] === "variables";
        $asset = Assets::getInstance()->loadSCSSAsset($parameters["file"]);

        $variables = $this->getVariables();

        if ($isVars) {
            $css = sprintf(
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

            $scss = $compiler->compileString($asset->getContent(), $asset->getFilePath());
            $css = $scss->getCss();
            $map = $scss->getSourceMap();
            if ($map) {
                $css .= "\n\n//# sourceMappingURL=data:application/json;charset=utf-8;base64," . base64_encode($map);
            }
        }

        return ['css' => $css, 'asset' => $asset];
    }
}
