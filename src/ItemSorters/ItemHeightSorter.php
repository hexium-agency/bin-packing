<?php

declare(strict_types=1);

namespace Hexium\BinPacking\ItemSorters;

use Hexium\BinPacking\Item;
use Hexium\BinPacking\ItemList;
use Hexium\BinPacking\ItemSorter;

class ItemHeightSorter implements ItemSorter
{
    public function sort(ItemList $items): ItemList
    {
        $sortedItems = $items->items();
        usort($sortedItems, static fn(Item $a, Item $b) => $b->height() <=> $a->height());

        return new ItemList($sortedItems);
    }
}
