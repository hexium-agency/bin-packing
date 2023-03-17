<?php

declare(strict_types=1);

namespace Hexium\BinPacking;

interface PackingStrategy
{
    /**
     * @param array<Item> $items
     * @return BinResultCollection
     */
    public function pack(array $items): BinResultCollection;
}
