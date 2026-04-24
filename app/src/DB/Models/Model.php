<?php

namespace DB\Models;

use DB\DB;

interface Model
{
    public function __construct(DB $db);
}
