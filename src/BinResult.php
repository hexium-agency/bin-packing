<?php

declare(strict_types=1);

namespace Hexium\BinPacking;

class BinResult implements \Countable
{
    /**
     * @var PackedItem[]
     */
    public array $items = [];

    public function __construct(
        public Bin $bin,
    ) {
    }

    public function add(PackedItem $item): void
    {
        $this->items[] = $item;
    }

    public function count(): int
    {
        return count($this->items);
    }
}
