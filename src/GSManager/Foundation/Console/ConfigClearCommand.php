<?php

namespace GSManager\Foundation\Console;

use GSManager\Console\Command;
use GSManager\Filesystem\Filesystem;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'config:clear')]
class ConfigClearCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'config:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove the configuration cache file';

    /**
     * The filesystem instance.
     *
     * @var \GSManager\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Create a new config clear command instance.
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
        $this->files->delete($this->gsmanager->getCachedConfigPath());

        $this->components->info('Configuration cache cleared successfully.');
    }
}
