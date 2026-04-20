<?php

namespace Renderers;

use Smarty\Smarty;

class HtmlRenderer extends BaseRenderer {
    public function render(mixed $data = null, array | null $parameters = null): void {
        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__ . '/templates');
        $smarty->setCompileDir(sys_get_temp_dir());
        $tplName = $parameters['tplName'] ?? "default";

        header("Content-type: text/html");
        parent::render( $smarty->fetch("pages/" . $tplName . '.tpl'));
    }
}