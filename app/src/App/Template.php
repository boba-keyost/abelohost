<?php

namespace App;

use Extensions\ConfigExtension;
use Features\Assets\Assets;
use Features\Assets\AssetType;
use Smarty\Smarty;

class Template
{
    use ConfigExtension;

    protected ?Smarty $smarty = null;

    public function smarty(): Smarty
    {
        if (is_null($this->smarty)) {
            $this->smarty = new Smarty();
            $tplDir = Assets::getInstance()->getAssetDirPath(AssetType::Templates);
            $this->smarty->setTemplateDir($tplDir);
            if ($this->getConfig()->debug) {
                $this->smarty->setCaching(Smarty::CACHING_OFF);
            }
            $this->smarty->setCompileDir(sys_get_temp_dir());
            $this->smarty->assign("config", $this->getConfig());
            $this->smarty->registerPlugin(
                "modifier",
                "var_dump",
                "var_dump"
            );
            $this->smarty->registerPlugin(
                "modifier",
                "urldecode",
                "urldecode"
            );
            $this->smarty->registerPlugin(
                "modifier",
                "var_export",
                fn (mixed $var) => var_export($var, true)
            );
            $this->smarty->registerPlugin(
                "modifier",
                "prepare_error",
                function (mixed $err) {
                    return Error::fromError($err)->unwrap();
                }
            );
            $this->smarty->registerPlugin(
                "modifier",
                "normalize_query",
                function (mixed $params): array {
                    $normalized = [];
                    if (!empty($params)) {
                        $str = http_build_query($params);
                        foreach (explode("&", $str) as $part) {
                            [$key, $val] = explode('=', $part);
                            $normalized[$key] = $val;
                        }
                    }
                    return $normalized;
                }
            );
        }
        return $this->smarty;
    }
}
