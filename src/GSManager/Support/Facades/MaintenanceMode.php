<?php

namespace GSManager\Support\Facades;

use GSManager\Foundation\MaintenanceModeManager;

class MaintenanceMode extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return MaintenanceModeManager::class;
    }
}
