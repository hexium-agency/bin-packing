<?php

use Hexium\BinPacking\Bin;
use Hexium\BinPacking\CannotPackItems;
use Hexium\BinPacking\Packer;
use Hexium\BinPacking\Test\TestItem;

it('creates a packer', function () {
    $packer = new Packer();

    $bins = [];
    $items = [];

    $packedItems = $packer->pack($bins, $items);

    expect($packer)->toBeInstanceOf(Packer::class)
        ->and($packedItems)->toBeArray()->toHaveCount(0);
});

it('cannot pack item bigger than bin on both sides', function () {
    $packer = new Packer();

    $bins = [new Bin(width: 10, height: 10)];

    $items = [new TestItem(width: 11, height: 11, id:"item1")];

    $packedItems = $packer->pack($bins, $items);

    expect($packedItems)->toBeArray()->toHaveCount(0);
})->throws(CannotPackItems::class);

it('can place an item in a bin', function () {
    $packer = new Packer();

    $bins = [new Bin(width: 10, height: 10)];

    $items = [new TestItem(width: 10, height: 10, id:"item1")];

    $packedItems = $packer->pack($bins, $items);
    $packedItem = $packedItems[0];

    expect($packedItems)->toBeArray()->toHaveCount(1)
        ->and($packedItems[0]->bin)->toBe($bins[0])
        ->and($packedItems[0]->item)->toBe($items[0])
        ->and($packedItem->xPosition)->toBe(0)
        ->and($packedItem->yPosition)->toBe(0);
});

it('can place two items side by side in a bin', function () {
    $packer = new Packer();

    $bins = [new Bin(width: 20, height: 10)];

    $items = [
        new TestItem(width: 10, height: 10, id:"item1"),
        new TestItem(width: 10, height: 10, id:"item2"),
    ];

    $packedItems = $packer->pack($bins, $items);

    expect($packedItems)->toBeArray()->toHaveCount(2);
});
