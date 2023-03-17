<?php

use Hexium\BinPacking\Bin;
use Hexium\BinPacking\ItemCannotFitInAnyBins;
use Hexium\BinPacking\PackingStrategies\DefaultStrategy;
use Hexium\BinPacking\Test\TestItem;

it('creates a packer', function () {
    $packer = new DefaultStrategy();

    $bins = [];
    $items = [];

    $packedItems = $packer->pack($bins, $items);

    expect($packer)->toBeInstanceOf(DefaultStrategy::class)
        ->and($packedItems)->toHaveCount(0);
});

it('cannot pack item bigger than bin on both sides', function () {
    $packer = new DefaultStrategy();

    $bins = [new Bin(width: 10, height: 10)];

    $items = [new TestItem(width: 11, height: 11, id:"item1")];

    $packedItems = $packer->pack($bins, $items);

    expect($packedItems)->toBeArray()->toHaveCount(0);
})->throws(ItemCannotFitInAnyBins::class);

it('can place an item in a bin', function () {
    $packer = new DefaultStrategy();

    $bins = [new Bin(width: 10, height: 10)];

    $items = [new TestItem(width: 10, height: 10, id:"item1")];

    $resultBins = $packer->pack($bins, $items);
    $firstBin = $resultBins->first();

    $packedItem = $firstBin->items[0];

    expect($firstBin)->toHaveCount(1)
        ->and($firstBin->bin)->toBe($bins[0])
        ->and($firstBin->items)->toHaveCount(1)
        ->and($packedItem->xPosition)->toBe(0)
        ->and($packedItem->yPosition)->toBe(0);
});

it('can place two items side by side in a bin', function () {
    $packer = new DefaultStrategy();

    $bins = [new Bin(width: 20, height: 10)];

    $items = [
        new TestItem(width: 10, height: 10, id:"item1"),
        new TestItem(width: 10, height: 10, id:"item2"),
    ];

    $resultBins = $packer->pack($bins, $items);
    $firstBin = $resultBins->first();

    expect($firstBin)->toHaveCount(2)
        ->and($firstBin->items[0]->xPosition)->toBe(0)
        ->and($firstBin->items[0]->yPosition)->toBe(0)
        ->and($firstBin->items[1]->xPosition)->toBe(10)
        ->and($firstBin->items[1]->yPosition)->toBe(0);
});

it('places second item below the first one if height available', function () {
    $packer = new DefaultStrategy();

    $bins = [new Bin(width: 20, height: 20)];

    $items = [
        new TestItem(width: 10, height: 10, id:"item1"),
        new TestItem(width: 10, height: 10, id:"item2"),
    ];

    $resultBins = $packer->pack($bins, $items);
    $firstBin = $resultBins->first();

    expect($firstBin)->toHaveCount(2)
        ->and($firstBin->items[0]->xPosition)->toBe(0)
        ->and($firstBin->items[0]->yPosition)->toBe(0)
        ->and($firstBin->items[1]->xPosition)->toBe(0)
        ->and($firstBin->items[1]->yPosition)->toBe(10);
});

it('places complex items', function () {
    $packer = new DefaultStrategy();

    $bins = [new Bin(width: 8, height: 8)];

    $items = [
        new TestItem(width: 1, height: 5, id:"item1"),
        new TestItem(width: 2, height: 3, id:"item2"),
        new TestItem(width: 2, height: 3, id:"item3"),
        new TestItem(width: 2, height: 2, id:"item4"),
        new TestItem(width: 1, height: 2, id:"item5"),
    ];

    $resultBins = $packer->pack($bins, $items);
    $firstBin = $resultBins->first();

    expect($firstBin)->toHaveCount(5)
        ->and($firstBin->items[0]->xPosition)->toBe(0)->and($firstBin->items[0]->yPosition)->toBe(0)
        ->and($firstBin->items[1]->xPosition)->toBe(0)->and($firstBin->items[1]->yPosition)->toBe(5)
        ->and($firstBin->items[2]->xPosition)->toBe(1)->and($firstBin->items[2]->yPosition)->toBe(0)
        ->and($firstBin->items[3]->xPosition)->toBe(1)->and($firstBin->items[3]->yPosition)->toBe(3)
        ->and($firstBin->items[4]->xPosition)->toBe(2)->and($firstBin->items[4]->yPosition)->toBe(5)
    ;
});

it('creates arbitrarily a new node when none suits for the item', function () {
    $packer = new DefaultStrategy();

    $bins = [new Bin(width: 9, height: 6)];

    $items = [
        new TestItem(width: 3, height: 3, id:"item1"),
        new TestItem(width: 8, height: 3, id:"item2"),
        new TestItem(width: 1, height: 4, id:"item3"),
    ];

    $resultBins = $packer->pack($bins, $items);
    $firstBin = $resultBins->first();

    expect($firstBin)->toHaveCount(3);
});

it('creates arbitrarily a new node and grow the bin if needed', function () {
    $packer = new DefaultStrategy();

    $bins = [new Bin(width: 9, height: 4, canGrowRight: true)];

    $items = [
        new TestItem(width: 5, height: 2, id:"item1"),
        new TestItem(width: 4, height: 2, id:"item2"),
        new TestItem(width: 4, height: 2, id:"item3"),
        new TestItem(width: 4, height: 2, id:"item4"),
        new TestItem(width: 4, height: 2, id:"item5"),
    ];

    $resultBins = $packer->pack($bins, $items);
    $firstBin = $resultBins->first();

    expect($firstBin)->toHaveCount(5);
});

it('grows the bin if possible when an item cannot fit in at least one bin', function () {
    $packer = new DefaultStrategy();

    $bins = [new Bin(width: 9, height: 9, canGrowRight: true)];

    $items = [
        new TestItem(width: 10, height: 9, id:"item1"),
    ];

    $resultBins = $packer->pack($bins, $items);
    $firstBin = $resultBins->first();

    expect($firstBin)->toHaveCount(1);
});


it('can allow an item to exceed width', function () {
    $packer = new DefaultStrategy();

    $bin = new Bin(width: 8, height: 8, canExceedWidth: true);

    $item = new TestItem(width: 9, height: 8, id: "item1");

    $resultBins = $packer->pack([$bin], [$item]);
    $firstBin = $resultBins->first();

    expect($firstBin)->toHaveCount(1);
});

it('can allow an item to exceed height', function () {
    $packer = new DefaultStrategy();

    $bin = new Bin(width: 8, height: 8, canExceedHeight: true);

    $item = new TestItem(width: 1, height: 9, id: "item1");

    $resultBins = $packer->pack([$bin], [$item]);
    $firstBin = $resultBins->first();

    expect($firstBin)->toHaveCount(1);
});
