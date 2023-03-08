<?php

declare(strict_types=1);

namespace Hexium\BinPacking;

readonly class PackedItem
{
    public function __construct(
        public Item $item,
        public Bin $bin,
        public int $xPosition,
        public int $yPosition,
    ) {
    }
}
