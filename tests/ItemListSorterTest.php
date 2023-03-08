<?php

use Hexium\BinPacking\ItemList;
use Hexium\BinPacking\ItemSorters\ItemAreaSorter;
use Hexium\BinPacking\ItemSorters\ItemHeightSorter;
use Hexium\BinPacking\Test\TestItem;

it('sort items by height', function () {
    $items = new ItemList([
        new TestItem(width: 10, height: 10, id: "item1"),
        new TestItem(width: 10, height: 20, id: "item2"),
    ]);

    $sorter = new ItemHeightSorter();

    $sortedItems = $sorter->sort($items);

    expect($sortedItems->items())->toBeArray()->toHaveCount(2)
        ->and($sortedItems->at(0)->id())->toBe("item2")
        ->and($sortedItems->at(1)->id())->toBe("item1");
});

it('sorts items by area', function (){
    $items = new ItemList([
        new TestItem(width: 10, height: 10, id: "item1"),
        new TestItem(width: 10, height: 20, id: "item2"),
        new TestItem(width: 20, height: 20, id: "item3"),
        new TestItem(width: 200, height: 10, id: "item4"),
    ]);

    $sorter = new ItemAreaSorter();

    $sortedItems = $sorter->sort($items);

    expect($sortedItems->items())->toBeArray()->toHaveCount(4)
        ->and($sortedItems->at(0)->id())->toBe("item4")
        ->and($sortedItems->at(1)->id())->toBe("item3")
        ->and($sortedItems->at(3)->id())->toBe("item1");
});
