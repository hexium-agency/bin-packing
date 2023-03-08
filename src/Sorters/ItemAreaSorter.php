<?php

declare(strict_types=1);

namespace Hexium\BinPacking\Sorters;

use Hexium\BinPacking\Item;
use Hexium\BinPacking\ItemList;
use Hexium\BinPacking\ItemSorter;

class ItemAreaSorter implements ItemSorter
{
    public function sort(ItemList $items): ItemList
    {
        $sortedItems = $items->items();
        usort($sortedItems, static fn(Item $a, Item $b) => $b->area() <=> $a->area());

        return new ItemList($sortedItems);
    }
}
