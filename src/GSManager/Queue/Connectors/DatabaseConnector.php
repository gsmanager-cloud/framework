<?php

namespace GSManager\Queue\Connectors;

use GSManager\Database\ConnectionResolverInterface;
use GSManager\Queue\DatabaseQueue;

class DatabaseConnector implements ConnectorInterface
{
    /**
     * Database connections.
     *
     * @var \GSManager\Database\ConnectionResolverInterface
     */
    protected $connections;

    /**
     * Create a new connector instance.
     *
     * @param  \GSManager\Database\ConnectionResolverInterface  $connections
     */
    public function __construct(ConnectionResolverInterface $connections)
    {
        $this->connections = $connections;
    }

    /**
     * Establish a queue connection.
     *
     * @param  array  $config
     * @return \GSManager\Contracts\Queue\Queue
     */
    public function connect(array $config)
    {
        return new DatabaseQueue(
            $this->connections->connection($config['connection'] ?? null),
            $config['table'],
            $config['queue'],
            $config['retry_after'] ?? 60,
            $config['after_commit'] ?? null
        );
    }
}
