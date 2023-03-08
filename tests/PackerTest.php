<?php

use Hexium\BinPacking\Bin;
use Hexium\BinPacking\CannotPackItems;
use Hexium\BinPacking\Item;
use Hexium\BinPacking\Packer;

it('creates a packer', function () {
    $packer = new Packer();

    $bins = [];
    $items = [];

    $packedItems = $packer->pack($bins, $items);

    expect($packer)->toBeInstanceOf(Packer::class);
    expect($packedItems)->toBeArray()->toHaveCount(0);
});

it('cannot pack item bigger than bin on both sides', function () {
    $packer = new Packer();

    $bins = [new Bin(width: 10, height: 10)];

    $items = [new Item(width: 11, height: 11)];

    $packedItems = $packer->pack($bins, $items);

    expect($packedItems)->toBeArray()->toHaveCount(0);
})->throws(CannotPackItems::class);

it('can place an item in a bin', function () {
    $packer = new Packer();

    $bins = [new Bin(width: 10, height: 10)];

    $items = [new Item(width: 10, height: 10)];

    $packedItems = $packer->pack($bins, $items);
    $packedItem = $packedItems[0];

    expect($packedItems)->toBeArray()->toHaveCount(1);

    expect($packedItems[0]->bin)->toBe($bins[0]);
    expect($packedItems[0]->item)->toBe($items[0]);

    expect($packedItem->xPosition)->toBe(0);
    expect($packedItem->yPosition)->toBe(0);
});

it('can place two items side by side in a bin', function () {
    $packer = new Packer();

    $bins = [new Bin(width: 20, height: 10)];

    $items = [
        new Item(width: 10, height: 10),
        new Item(width: 10, height: 10),
    ];

    $packedItems = $packer->pack($bins, $items);

    expect($packedItems)->toBeArray()->toHaveCount(2);
});
