<?php

namespace DB\Models\Types;

use Throwable;

trait WithCreated
{
    public DateTime|string $created_at;
    public DateTime|string $updated_at;

    public function initCreatedFields(): void
    {
        try {
            if (is_string($this->created_at)) {
                $this->created_at = new DateTime($this->created_at);
            }
            if (is_string($this->updated_at)) {
                $this->updated_at = new DateTime($this->updated_at);
            }
        } catch (Throwable $e) {
            // do nothing
        }
    }
}
