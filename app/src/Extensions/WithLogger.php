<?php

namespace Extensions;

use App\Logger;

interface WithLogger {
    public function setLogger(Logger $logger): static;
    public function getLogger(): Logger;
}