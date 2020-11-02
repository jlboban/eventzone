<?php

namespace App\Service;

class RangeMapper
{
    public function map(float $value, float $xMin, float $xMax, float $yMin, float $yMax): float
    {
        return ($value - $xMin) / ($xMax - $xMin) * ($yMax - $yMin) + $yMin;
    }
}
