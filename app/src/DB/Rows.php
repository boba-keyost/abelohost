<?php

namespace DB;

use Countable;
use Iterator;
use JsonSerializable;
use PDOStatement;

class Rows implements Iterator, Countable, JsonSerializable
{
    protected PDOStatement $st;
    protected array $rows = [];
    protected array $keys = [];
    protected int $i = 0;
    protected bool $fetchFinished = false;

    public function __construct(PDOStatement $st)
    {
        $this->st = $st;
    }

    protected function getKeyFromRow(mixed $row): mixed
    {
        $key = $this->i;
        if (is_array($row) && array_key_exists("id", $row)) {
            $key = $row["key"];
        } elseif (is_object($row) && property_exists($row, "id")) {
            $key = $row->id;
        }

        return $key;
    }

    protected function getRow(?int $i = null): mixed
    {
        if (is_null($i)) {
            $i = $this->i;
        }

        if (array_key_exists($i, $this->rows)) {
            return $this->rows[$i];
        } elseif (!$this->fetchFinished) {
            $res = $this->st->fetch();
            if ($res !== false) {
                $this->rows[$i] = $res;
                $this->keys[$i] = $this->getKeyFromRow($res);
            } else {
                $this->fetchFinished = true;
            }
        }

        return $this->rows[$i] ?? null;
    }

    public function getByKey(mixed $key): mixed
    {
        $i = array_search($key, $this->keys, true);
        if ($i !== false) {
            return $this->getRow($i);
        } elseif (!$this->fetchFinished) {
            $cur = $this->i;
            while ($this->valid()) {
                if ($this->key() === $key) {
                    return $this->current();
                }
                $this->next();
            }
            $this->i = $cur;
        }

        return null;
    }

    public function current(): mixed
    {
        return $this->getRow();
    }

    public function next(): void
    {
        $this->i++;
    }

    public function key(): mixed
    {
        return $this->keys[$this->i];
    }

    public function valid(): bool
    {
        return $this->getRow() !== null;
    }

    public function rewind(): void
    {
        $this->i = 0;
    }

    public function count(): int
    {
        return $this->fetchFinished ? count($this->keys) : $this->st->rowCount();
    }

    public function toArray(): array
    {
        if (!$this->fetchFinished) {
            while ($this->valid()) {
                $this->next();
            }
        }
        return $this->rows;
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public function __toString(): string
    {
        return implode(", ", $this->toArray());
    }
}
