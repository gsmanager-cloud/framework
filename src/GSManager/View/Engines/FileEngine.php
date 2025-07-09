<?php

namespace GSManager\View\Engines;

use GSManager\Contracts\View\Engine;
use GSManager\Filesystem\Filesystem;

class FileEngine implements Engine
{
    /**
     * The filesystem instance.
     *
     * @var \GSManager\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Create a new file engine instance.
     *
     * @param  \GSManager\Filesystem\Filesystem  $files
     */
    public function __construct(Filesystem $files)
    {
        $this->files = $files;
    }

    /**
     * Get the evaluated contents of the view.
     *
     * @param  string  $path
     * @param  array  $data
     * @return string
     */
    public function get($path, array $data = [])
    {
        return $this->files->get($path);
    }
}
