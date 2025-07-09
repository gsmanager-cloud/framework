<?php

namespace GSManager\Foundation\Console;

use GSManager\Console\Command;
use GSManager\Filesystem\Filesystem;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'route:clear')]
class RouteClearCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'route:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove the route cache file';

    /**
     * The filesystem instance.
     *
     * @var \GSManager\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Create a new route clear command instance.
     *
     * @param  \GSManager\Filesystem\Filesystem  $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->files->delete($this->gsmanager->getCachedRoutesPath());

        $this->components->info('Route cache cleared successfully.');
    }
}
