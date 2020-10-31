<?php

namespace App\Service;

class RangeMapper
{
    public function map($value, $xMin, $xMax, $yMin, $yMax)
    {
        return ($value - $xMin) / ($xMax - $xMin) * ($yMax - $yMin) + $yMin;
    }
}
