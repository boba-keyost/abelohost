<?php

namespace App;

use Config\Config;

class Logger {
    const int LEVEL_DEBUG = 10;
    const int LEVEL_INFO = 3;
    const int LEVEL_WARNING = 2;
    const int LEVEL_ERROR = 1;
    const int LEVEL_CRITICAL = 0;

    protected int $level = 0;
    protected array $messages = [];

    protected string $logFile = 'php://stderr';

    protected static ?Logger $defaultLogger = null;
    public static function getDefault(): static
    {
        if (is_null(static::$defaultLogger)) {
            static::$defaultLogger = new Logger();
        }
        return static::$defaultLogger;
    }

    public function __construct(?int $level = null) {
        if (is_null($level)) {
            $level = Config::getInstance()->logLevel;
        }
        $this->level = $level;
    }

    public function setLogFile(string $file): static
    {
        $this->logFile = $file;
        return $this;
    }

    public function getLogFile(): string
    {
        return $this->logFile;
    }

    public function setLevel(int $level): static
    {
        $this->level = $level;
        return $this;
    }

    public function getLevel(): int {
        return $this->level;
    }

    public function clearMessages(): static
    {
        $this->messages = [];
        return $this;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function debug(string... $messages): void {
        $this->write(static::LEVEL_DEBUG, "[DEBUG]: ", ...$messages);
    }
    public function info(string... $messages): void {
        $this->write(static::LEVEL_INFO, "[INFO]: ", ...$messages);
    }
    public function warning(string... $messages): void {
        $this->write(static::LEVEL_WARNING, "[WARNING]: ", ...$messages);
    }
    public function error(string... $messages): void {
        $this->write(static::LEVEL_ERROR, "[ERROR]: ", ...$messages);
    }
    public function critical(string... $messages): void {
        $this->write(static::LEVEL_CRITICAL, "[CRITICAL]: ", ...$messages);
    }

    protected function write(int $level, string... $messages): void {
        $message = date("Y-m-d H:i:s") . " " . implode(" ", $messages);
        $this->messages[] = $message;
        if ($level <= $this->level) {
            $fh = fopen($this->logFile,'a');
            fwrite($fh,$message);
            fclose($fh);
        }
    }
}