<?php

use Hexium\BinPacking\Bin;
use Hexium\BinPacking\Node;
use Hexium\BinPacking\Test\TestItem;

it('places items', function () {
    $bin = new Bin(100, 100);

    expect($bin->rectangles)->toBeArray()->toHaveCount(0)
        ->and($bin->nodeList())->toHaveCount(1);

    $item = new TestItem(10, 10, "item1");

    // We place a rectangle in the empty bin
    // Thus it removes the initial node
    // And adds two new nodes
    $bin->placeItem($item, 0, 0);

    expect($bin->rectangles)->toBeArray()->toHaveCount(1)
        ->and($bin->nodeList())->toHaveCount(2)
        ->and($bin->nodeList()->at(0, 10))->toBeInstanceOf(Node::class)
        ->and($bin->nodeList()->at(10, 0))->toBeInstanceOf(Node::class);

    // We then add another rectangle which is placed below the first one
    // Its height is defined as it fill the gap between the first rectangle and the bottom of the bin
    // Thus it removes the node from the bottom left of the first rectangle
    // And add no new node as no space is available
    $item = new TestItem(10, 90, "item2");

    $bin->placeItem($item, 0, 10);

    expect($bin->rectangles)->toBeArray()->toHaveCount(2)
        ->and($bin->nodeList())->toHaveCount(2)
        ->and($bin->nodeList()->at(10, 0))->toBeInstanceOf(Node::class)
        ->and($bin->nodeList()->at(10, 10))->toBeInstanceOf(Node::class);

    $item = new TestItem(20, 30, "item3");

    $bin->placeItem($item, 10, 0);

    expect($bin->rectangles)->toBeArray()->toHaveCount(3)
        ->and($bin->nodeList())->toHaveCount(2)
        ->and($bin->nodeList()->at(30, 0))->toBeInstanceOf(Node::class)
        ->and($bin->nodeList()->at(10, 30))->toBeInstanceOf(Node::class);

    $item = new TestItem(10, 10, "item4");

    $bin->placeItem($item, 30, 0);

    expect($bin->rectangles)->toBeArray()->toHaveCount(4)
        ->and($bin->nodeList())->toHaveCount(3)
        ->and($bin->nodeList()->at(40, 0))->toBeInstanceOf(Node::class)
        ->and($bin->nodeList()->at(10, 30))->toBeInstanceOf(Node::class)
        ->and($bin->nodeList()->at(30, 10))->toBeInstanceOf(Node::class);
});

it('can say if an item can fit for a given node', function () {
    $bin = new Bin(100, 100);

    $item = new TestItem(10, 10, "item1");

    $bin->placeItem($item, 0, 0);

    $bigItem = new TestItem(10, 100, "item2");

    $node = $bin->nodeList()->at(10, 0);

    expect($bin->canFit($bigItem, $node))->toBeTrue();

    $node = $bin->nodeList()->at(0, 10);

    expect($bin->canFit($bigItem, $node))->toBeFalse();
});

it('check it cannot place an item inside an existing rectangle', function () {
    $item1 = new TestItem(width: 1, height: 5, id: "item1");
    $item2 = new TestItem(width: 2, height: 3, id: "item2");
    $item3 = new TestItem(width: 2, height: 5, id: "item3");
    $item4 = new TestItem(width: 2, height: 2, id: "item4");

    $bin = new Bin(8, 8);

    $bin->placeItem($item1, 0, 0);
    $bin->placeItem($item2, 0, 5);
    $bin->placeItem($item3, 1, 0);

    expect($bin->canFit($item4, $bin->nodeList()->at(1, 5)))->toBeFalse();
});

it('cannot place an item having at least one corner in another rectangle', function () {
    $bin = new Bin(8, 8);

    $item1 = new TestItem(width: 1, height: 5, id: "item1");
    $bin->placeItem($item1, 0, 0);

    $item2 = new TestItem(width: 1, height: 4, id: "item2");
    $bin->placeItem($item2, 1, 0);

    $item3 = new TestItem(width: 4, height: 4, id: "item3");
    $bin->placeItem($item3, 1, 4);

    $item4 = new TestItem(width: 1, height: 3, id: "item4");
    $bin->placeItem($item4, 0, 5);

    $item5 = new TestItem(width: 1, height: 3, id: "item5");
    $bin->placeItem($item5, 2, 0);

    $item6 = new TestItem(width: 1, height: 3, id: "item6");
    expect($bin->canFit($item6, $bin->nodeList()->at(2, 3)))->toBeFalse();
    $bin->placeItem($item6, 3, 0);

    $item7 = new TestItem(width: 3, height: 3, id: "item7");
    expect($bin->canFit($item7, $bin->nodeList()->at(3, 3)))->toBeFalse();
});

it('cannot place an item having no corner in another rectangle but overflowing it', function () {
    $bin = new Bin(8, 9);

    $item1 = new TestItem(width: 1, height: 5, id: "item1");
    $bin->placeItem($item1, 0, 0);

    $item2 = new TestItem(width: 1, height: 4, id: "item2");
    $bin->placeItem($item2, 1, 0);

    $item3 = new TestItem(width: 4, height: 4, id: "item3");
    $bin->placeItem($item3, 1, 4);

    $item4 = new TestItem(width: 1, height: 3, id: "item4");
    $bin->placeItem($item4, 2, 0);

    $item5 = new TestItem(width: 1, height: 3, id: "item5");
    $bin->placeItem($item5, 3, 0);

    $item6 = new TestItem(width: 1, height: 6, id: "item6");
    expect($bin->canFit($item6, $bin->nodeList()->at(3, 3)))->toBeFalse();
});
