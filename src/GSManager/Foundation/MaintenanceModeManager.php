<?php

namespace GSManager\Foundation;

use GSManager\Support\Manager;

class MaintenanceModeManager extends Manager
{
    /**
     * Create an instance of the file based maintenance driver.
     *
     * @return \GSManager\Foundation\FileBasedMaintenanceMode
     */
    protected function createFileDriver(): FileBasedMaintenanceMode
    {
        return new FileBasedMaintenanceMode();
    }

    /**
     * Create an instance of the cache based maintenance driver.
     *
     * @return \GSManager\Foundation\CacheBasedMaintenanceMode
     *
     * @throws \GSManager\Contracts\Container\BindingResolutionException
     */
    protected function createCacheDriver(): CacheBasedMaintenanceMode
    {
        return new CacheBasedMaintenanceMode(
            $this->container->make('cache'),
            $this->config->get('app.maintenance.store') ?: $this->config->get('cache.default'),
            'gsmanager:foundation:down'
        );
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver(): string
    {
        return $this->config->get('app.maintenance.driver', 'file');
    }
}
