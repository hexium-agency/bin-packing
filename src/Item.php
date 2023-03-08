<?php

declare(strict_types=1);

namespace Hexium\BinPacking;

class Item
{
    public function __construct(public int $width, public int $height)
    {
    }
}
