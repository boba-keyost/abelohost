<?php

namespace Extensions;

use DB\DB;

interface WithDB
{
    public function setDb(DB $db): static;
    public function getDb(): DB;
}
