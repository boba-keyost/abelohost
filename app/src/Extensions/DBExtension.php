<?php

namespace Extensions;

use DB\DB;

trait DBExtension
{
    protected mixed $dbGetter = null;
    protected ?DB $db = null;

    public function setDb(DB $db): static
    {
        $this->db = $db;
        return $this;
    }

    public function getDbGetter(): ?callable
    {
        return $this->dbGetter;
    }

    public function setDbGetter(?callable $getter): static
    {
        if (is_callable($getter)) {
            $this->dbGetter = $getter;
        }
        return $this;
    }

    public function getDb(bool $noInit = false): DB
    {
        if (is_null($this->db) && !$noInit) {
            if (!is_null($this->dbGetter)) {
                $this->db = call_user_func($this->dbGetter);
            } else {
                $this->db = DB::getDefault();
            }
        }
        return $this->db;
    }
}
