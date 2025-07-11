<?php

namespace GSManager\Contracts\Filesystem;

interface Factory
{
    /**
     * Get a filesystem implementation.
     *
     * @param  string|null  $name
     * @return \GSManager\Contracts\Filesystem\Filesystem
     */
    public function disk($name = null);
}
