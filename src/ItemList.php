<?php

declare(strict_types=1);

namespace Hexium\BinPacking;

class ItemList
{
    /**
     * @var Item[]
     */
    private array $items;

    /**
     * @param Item[] $items
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function items(): array
    {
        return $this->items;
    }

    public function at(int $index): Item
    {
        if (!isset($this->items[$index])) {
            throw new \OutOfBoundsException();
        }

        return $this->items[$index];
    }
}
