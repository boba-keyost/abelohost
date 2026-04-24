<?php

namespace DB\Models\Types;

use DateTime;
use Throwable;

trait WithSoftDelete
{
    public DateTime|string|null $deleted_at;

    public function initDeletedFields(): void
    {
        try {
            if (is_string($this->deleted_at)) {
                $this->deleted_at = new DateTime($this->deleted_at);
            }
        } catch (Throwable $e) {
            // do nothing
        }
    }
}
