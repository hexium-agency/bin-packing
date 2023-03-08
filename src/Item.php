<?php

declare(strict_types=1);

namespace Hexium\BinPacking;

interface Item
{
    public function id(): string ;

    public function width(): int;

    public function height(): int;

    public function area(): int;
}
