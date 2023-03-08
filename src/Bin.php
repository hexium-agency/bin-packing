<?php

declare(strict_types=1);

namespace Hexium\BinPacking;

readonly class Bin
{
    public function __construct(public int $width, public int $height)
    {
    }
}
