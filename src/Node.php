<?php

declare(strict_types=1);

namespace Hexium\BinPacking;

readonly class Node
{
    public function __construct(public int $x, public int $y)
    {
    }
}
