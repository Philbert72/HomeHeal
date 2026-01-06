<?php

namespace App\Traits;

use App\Services\UnitConverterService;

trait ConvertsUnits
{
    protected function getUnitConverter(): UnitConverterService
    {
        return app(UnitConverterService::class);
    }
}