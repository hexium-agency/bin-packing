<?php

declare(strict_types=1);

namespace Hexium\BinPacking;

class PackedItem
{
    public function __construct(
        public readonly Item $item,
        public readonly Bin $bin,
        public readonly int $xPosition,
        public readonly int $yPosition,
    ) {
    }
}
