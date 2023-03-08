<?php

use Hexium\BinPacking\Bin;
use Hexium\BinPacking\Node;
use Hexium\BinPacking\Test\TestItem;

it('has a default node', function () {
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
