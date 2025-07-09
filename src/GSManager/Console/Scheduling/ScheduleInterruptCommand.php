<?php

namespace GSManager\Console\Scheduling;

use GSManager\Console\Command;
use GSManager\Contracts\Cache\Repository as Cache;
use GSManager\Support\Facades\Date;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'schedule:interrupt')]
class ScheduleInterruptCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'schedule:interrupt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Interrupt the current schedule run';

    /**
     * The cache store implementation.
     *
     * @var \GSManager\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * Create a new schedule interrupt command.
     *
     * @param  \GSManager\Contracts\Cache\Repository  $cache
     */
    public function __construct(Cache $cache)
    {
        parent::__construct();

        $this->cache = $cache;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->cache->put('gsmanager:schedule:interrupt', true, Date::now()->endOfMinute());

        $this->components->info('Broadcasting schedule interrupt signal.');
    }
}
