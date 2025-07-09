<?php

namespace GSManager\Database\Events;

use GSManager\Contracts\Database\Events\MigrationEvent as MigrationEventContract;
use GSManager\Database\Migrations\Migration;

abstract class MigrationEvent implements MigrationEventContract
{
    /**
     * A migration instance.
     *
     * @var \GSManager\Database\Migrations\Migration
     */
    public $migration;

    /**
     * The migration method that was called.
     *
     * @var string
     */
    public $method;

    /**
     * Create a new event instance.
     *
     * @param  \GSManager\Database\Migrations\Migration  $migration
     * @param  string  $method
     */
    public function __construct(Migration $migration, $method)
    {
        $this->method = $method;
        $this->migration = $migration;
    }
}
