<?php

declare(strict_types=1);

namespace Hexium\BinPacking;

class Bin
{
    public function __construct(public int $width, public int $height)
    {
    }
}
