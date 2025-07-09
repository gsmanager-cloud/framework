<?php

namespace GSManager\Foundation\Console;

use Exception;
use GSManager\Console\Command;
use GSManager\Foundation\Events\MaintenanceModeDisabled;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'up')]
class UpCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'up';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bring the application out of maintenance mode';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            if (! $this->gsmanager->maintenanceMode()->active()) {
                $this->components->info('Application is already up.');

                return 0;
            }

            $this->gsmanager->maintenanceMode()->deactivate();

            if (is_file(storage_path('framework/maintenance.php'))) {
                unlink(storage_path('framework/maintenance.php'));
            }

            $this->gsmanager->get('events')->dispatch(new MaintenanceModeDisabled());

            $this->components->info('Application is now live.');
        } catch (Exception $e) {
            $this->components->error(sprintf(
                'Failed to disable maintenance mode: %s.',
                $e->getMessage(),
            ));

            return 1;
        }

        return 0;
    }
}
