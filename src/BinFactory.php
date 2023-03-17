<?php

declare(strict_types=1);

namespace Hexium\BinPacking;

interface BinFactory
{
    public function create(): Bin;
}
