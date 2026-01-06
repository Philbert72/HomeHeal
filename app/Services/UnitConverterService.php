<?php

namespace App\Services;

class UnitConverterService
{
    const LB_TO_G = 453.592;
    const KG_TO_G = 1000;
    const FT_TO_M = 0.3048;
    const CM_TO_M = 0.01;

    public function weightToGrams(float $value, string $unit): float
    {
        return match (strtolower($unit)) {
            'g' => $value,
            'kg' => $value * self::KG_TO_G,
            'lb' => $value * self::LB_TO_G,
            default => throw new \InvalidArgumentException("Unsupported weight unit: $unit"),
        };
    }

    public function distanceToMeters(float $value, string $unit): float
    {
        return match (strtolower($unit)) {
            'm' => $value,
            'ft' => $value * self::FT_TO_M,
            'cm' => $value * self::CM_TO_M,
            default => throw new \InvalidArgumentException("Unsupported distance unit: $unit"),
        };
    }

}