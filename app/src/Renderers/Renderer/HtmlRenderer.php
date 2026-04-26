<?php

namespace Renderers\Renderer;

use App\Template;
use Features\Assets\Assets;
use Features\Assets\AssetType;
use Renderers\BaseRenderer;
use Renderers\Error;
use Smarty\Exception as SmartyException;
use Throwable;

class HtmlRenderer extends BaseRenderer
{
    public function renderContent(mixed $data = null, array | null $parameters = null): void
    {
        $error = null;
        if ($data instanceof Throwable) {
            $error = $data;
        }

        $templates = [
            $parameters['tplName'] ?? "default",
            "default",
            "error",
        ];
        if ($error) {
            $templates = array_reverse($templates);
        }
        $tplName = "";
        $pageTplName = array_reduce(
            $templates,
            function ($template, $templateName) use (&$tplName) {
                if (empty($template)) {
                    $pageTplName = "pages/" . $templateName . '.tpl';
                    if (Assets::getInstance()->isAssetExists(AssetType::Templates, $pageTplName)) {
                        $template = $pageTplName;
                        $tplName = $templateName;
                    }
                }
                return $template;
            },
            null
        );
        if ($error && ($error instanceof SmartyException || $error->getPrevious() instanceof SmartyException)) {
            $pageTplName = null;
        }

        if (!empty($pageTplName)) {
            $template = new Template();
            $template->setConfig($this->getConfig());

            $template->smarty()->assign(
                "styles",
                array_reduce(
                    [
                        'variables',
                        'base',
                        'components',
                        'tpl-' . $tplName,
                    ],
                    function (array $styles, string $file) {
                        if (Assets::getInstance()->isSCSSAssetExists($file)) {
                            $a = Assets::getInstance()->newSCSS($file);
                            $styles[] = $a->getFileName() . "?m=" . $a->getMTime();
                        }
                        return $styles;
                    },
                    [
                        "reset.css"
                    ]
                )
            );

            $template->smarty()->registerPlugin(
                "modifier",
                "code_to_string",
                function ($code, $modifier = "var_dump") use ($template) {
                    if (!empty($modifier)) {
                        $m = $template->smarty()->getModifierCallback($modifier);
                        if (!$m) {
                            throw Error::fromRenderer($this, "Unknown modifier '$modifier'");
                        }

                        return call_user_func($m, $code);
                    } else {
                        return var_export($code, true);
                    }
                }
            );

            $template->smarty()->assign("error", $error);
            if (!empty($data)) {
                if (is_array($data)) {
                    foreach ($data as $key => $value) {
                        $template->smarty()->assign($key, $value);
                    }
                }
            }
            parent::renderContent($template->smarty()->fetch($pageTplName));
        } else {
            TextRenderer::renderData($error->__toString());
        }
    }

    public static function renderData(mixed $data = null): void
    {
        header("Content-type: text/html");
        echo is_scalar($data) ? $data : json_encode($data);
    }
}
