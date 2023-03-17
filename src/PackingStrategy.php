<?php

declare(strict_types=1);

namespace Hexium\BinPacking;

interface PackingStrategy
{
    /**
     * @param array<Bin> $bins
     * @param array<Item> $items
     * @return BinResultCollection
     */
    public function pack(array $bins, array $items): BinResultCollection;
}
