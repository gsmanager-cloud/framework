<?php

namespace GSManager\Foundation\Console;

use GSManager\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'storage:unlink')]
class StorageUnlinkCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'storage:unlink';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete existing symbolic links configured for the application';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->links() as $link => $target) {
            if (! file_exists($link) || ! is_link($link)) {
                continue;
            }

            $this->gsmanager->make('files')->delete($link);

            $this->components->info("The [$link] link has been deleted.");
        }
    }

    /**
     * Get the symbolic links that are configured for the application.
     *
     * @return array
     */
    protected function links()
    {
        return $this->gsmanager['config']['filesystems.links'] ??
               [public_path('storage') => storage_path('app/public')];
    }
}
