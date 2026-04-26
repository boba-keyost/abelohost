<?php

namespace Extensions;

use Config\Config;

interface WithConfig
{
    public function setConfig(Config $config): static;
    public function getConfig(): Config;
}
