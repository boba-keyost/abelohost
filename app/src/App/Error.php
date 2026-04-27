<?php

namespace App;

use Config\Config;
use Exception;
use JsonSerializable;
use Throwable;

class Error extends Exception implements JsonSerializable
{
    protected const string PREFIX = "";
    protected bool $wrapped = false;

    public function __construct($message, $code = 0, ?Throwable $previous = null)
    {
        if (static::PREFIX) {
            $message = "[" . static::PREFIX . "] " . $message;
        }
        if (isset($code) && !is_int($code)) {
            $message = "[Code: " . $code . "]" . $message;
            $code = intval($code);
        }
        parent::__construct($message, $code ?? 0, $previous);
    }

    public function unwrap(): Throwable
    {
        if ($this->wrapped && $prev = $this->getPrevious()) {
            return $prev instanceof static ? $prev->unwrap() : $prev;
        }
        return $this;
    }

    public static function fromError(mixed $e = null, ?int $code = null, ?Throwable $previous = null): static
    {
        if (is_null($e)) {
            $e = "something went wrong";
        }
        if (!($e instanceof static)) {
            $wrapped = false;
            if ($e instanceof Throwable) {
                $message = $e->getMessage();
                if (is_null($previous)) {
                    $previous = $e;
                }
                $wrapped = true;
            } else {
                $message = $e;
            }
            $e = new static($message, $code, $previous);
            $e->wrapped = $wrapped;
        }

        return $e;
    }

    public static function notFound(string $message): static
    {
        return new static($message, 404);
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        $data = [
            "message" => $this->getMessage(),
            "code" => $this->getCode(),
        ];
        if (Config::defaultConfig()->debug) {
            $data["file"] = $this->getFile();
            $data["line"] = $this->getLine();
            $data["trace"] = $this->getTrace();
        }
        return $data;
    }
}
