<?php

namespace Extensions;

use App\Logger;

class LoggerExtension implements WithLogger {
    protected ?Logger $logger = null;

    public function setLogger(Logger $logger): static
    {
        $this->logger = $logger;
        return $this;
    }

    public function getLogger(): Logger {
        if (is_null($this->logger)) {
            $this->logger = Logger::getDefault();
        }
        return $this->logger;
    }
}