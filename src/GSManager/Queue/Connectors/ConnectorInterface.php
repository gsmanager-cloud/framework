<?php

namespace GSManager\Queue\Connectors;

interface ConnectorInterface
{
    /**
     * Establish a queue connection.
     *
     * @param  array  $config
     * @return \GSManager\Contracts\Queue\Queue
     */
    public function connect(array $config);
}
