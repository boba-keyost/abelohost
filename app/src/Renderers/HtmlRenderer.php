<?php

namespace Renderers;

use Smarty\Smarty;

function smarty_modifier_var_dump(mixed $vars)
{
    var_dump($vars);
}

class HtmlRenderer extends BaseRenderer
{
    public function render(mixed $data = null, array | null $parameters = null): void
    {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/templates');
        $smarty->setCompileDir(sys_get_temp_dir());
        $smarty->registerPlugin(
            "modifier",
            "var_dump",
            "var_dump"
        );

        $tplName = $parameters['tplName'] ?? "default";

        if (!empty($data)) {
            if (is_array($data)) {
                foreach ($data as $key => $value) {
                    $smarty->assign($key, $value);
                }
            }
        }

        header("Content-type: text/html");
        parent::render($smarty->fetch("pages/" . $tplName . '.tpl'));
    }
}
