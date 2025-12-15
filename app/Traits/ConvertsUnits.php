<?php

namespace App\Traits;

use App\Services\UnitConverterService;

trait ConvertsUnits
{
    /**
     * Get the UnitConverterService instance.
     */
    protected function getUnitConverter(): UnitConverterService
    {
        // Use Laravel's service container to resolve the service instance
        return app(UnitConverterService::class);
    }
}