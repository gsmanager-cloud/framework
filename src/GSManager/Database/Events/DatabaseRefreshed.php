<?php

namespace GSManager\Database\Events;

use GSManager\Contracts\Database\Events\MigrationEvent as MigrationEventContract;

class DatabaseRefreshed implements MigrationEventContract
{
    /**
     * Create a new event instance.
     *
     * @param  string|null  $database
     * @param  bool  $seeding
     */
    public function __construct(
        public ?string $database = null,
        public bool $seeding = false,
    ) {
    }
}
