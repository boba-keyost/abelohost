<?php

namespace DB;

use PDOException;

class Error extends \App\Error
{
    protected const string PREFIX = "DB";

    protected ?string $query = null;

    public static function fromPDO(PDOException $e, ?string $query = null): self
    {
        $message = $e->getMessage();
        if (!empty($query)) {
            $message .= " (query: " . $query . ")";
        }

        return new self($message, 0, $e)->setQuery($query);
    }

    public function getQuery(): ?string
    {
        return $this->query;
    }

    public function setQuery(?string $query): static
    {
        $this->query = $query;
        return $this;
    }
}
