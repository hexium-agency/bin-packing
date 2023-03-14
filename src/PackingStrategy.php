<?php

declare(strict_types=1);

namespace Hexium\BinPacking;

interface PackingStrategy
{
    /**
     * @param array<Bin> $bins
     * @param array<Item> $items
     * @return array
     */
    public function pack(array $bins, array $items): array;
}
