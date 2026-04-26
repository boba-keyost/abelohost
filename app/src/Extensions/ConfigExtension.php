<?php

namespace Extensions;

use Config\Config;

trait ConfigExtension
{
    protected ?Config $config = null;

    public function setConfig(Config $config): static
    {
        $this->config = $config;
        return $this;
    }

    public function getConfig(): Config
    {
        if (is_null($this->config)) {
            $this->config = Config::defaultConfig();
        }
        return $this->config;
    }
}
