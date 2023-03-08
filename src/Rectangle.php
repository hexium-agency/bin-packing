<?php

declare(strict_types=1);

namespace Hexium\BinPacking;

readonly class Rectangle
{
    public function __construct(
        public int $x,
        public int $y,
        public int $width,
        public int $height,
    ) {
    }
}
