<?php

namespace App\Services;

/**
 * Handles conversion between metric and imperial units, standardizing
 * all data storage to base units (Grams for weight, Meters for distance).
 */
class UnitConverterService
{
    // Conversion Constants (to convert 1 unit to the base unit)
    const LB_TO_G = 453.592; // 1 lb = 453.592 grams
    const KG_TO_G = 1000;    // 1 kg = 1000 grams
    const FT_TO_M = 0.3048;  // 1 foot = 0.3048 meters
    const CM_TO_M = 0.01;    // 1 cm = 0.01 meters

    /**
     * Converts a value from any supported weight unit to the base unit (Grams).
     * @param float $value The value to convert.
     * @param string $unit The unit of the value ('lb', 'kg', 'g').
     * @return float The value in Grams.
     */
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

    // You can add conversion methods back out of the base unit if needed for display
}