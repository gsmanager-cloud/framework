<?php

namespace GSManager\Contracts\Redis;

interface Factory
{
    /**
     * Get a Redis connection by name.
     *
     * @param  string|null  $name
     * @return \GSManager\Redis\Connections\Connection
     */
    public function connection($name = null);
}
