<?php

namespace GSManager\Queue\Connectors;

use GSManager\Queue\SyncQueue;

class SyncConnector implements ConnectorInterface
{
    /**
     * Establish a queue connection.
     *
     * @param  array  $config
     * @return \GSManager\Contracts\Queue\Queue
     */
    public function connect(array $config)
    {
        return new SyncQueue($config['after_commit'] ?? null);
    }
}
