<?php

namespace GSManager\Contracts\Cache;

interface Factory
{
    /**
     * Get a cache store instance by name.
     *
     * @param  string|null  $name
     * @return \GSManager\Contracts\Cache\Repository
     */
    public function store($name = null);
}
