<?php

namespace GSManager\Foundation\Testing;

use GSManager\Contracts\Console\Kernel;
use GSManager\Foundation\Testing\Traits\CanConfigureMigrationCommands;

trait DatabaseMigrations
{
    use CanConfigureMigrationCommands;

    /**
     * Define hooks to migrate the database before and after each test.
     *
     * @return void
     */
    public function runDatabaseMigrations()
    {
        $this->beforeRefreshingDatabase();
        $this->refreshTestDatabase();
        $this->afterRefreshingDatabase();

        $this->beforeApplicationDestroyed(function () {
            $this->gsm('migrate:rollback');

            RefreshDatabaseState::$migrated = false;
        });
    }

    /**
     * Refresh a conventional test database.
     *
     * @return void
     */
    protected function refreshTestDatabase()
    {
        $this->gsm('migrate:fresh', $this->migrateFreshUsing());

        $this->app[Kernel::class]->setArtisan(null);
    }

    /**
     * Perform any work that should take place before the database has started refreshing.
     *
     * @return void
     */
    protected function beforeRefreshingDatabase()
    {
        // ...
    }

    /**
     * Perform any work that should take place once the database has finished refreshing.
     *
     * @return void
     */
    protected function afterRefreshingDatabase()
    {
        // ...
    }
}
