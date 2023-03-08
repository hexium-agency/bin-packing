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

    expect($packedItems)->toBeArray()->toHaveCount(2)
        ->and($packedItems[0]->xPosition)->toBe(0)
        ->and($packedItems[0]->yPosition)->toBe(0)
        ->and($packedItems[1]->xPosition)->toBe(10)
        ->and($packedItems[1]->yPosition)->toBe(0);
});

it('places two second item below the first one if height available', function () {
    $packer = new Packer();

    $bins = [new Bin(width: 20, height: 20)];

    $items = [
        new TestItem(width: 10, height: 10, id:"item1"),
        new TestItem(width: 10, height: 10, id:"item2"),
    ];

    $packedItems = $packer->pack($bins, $items);

    expect($packedItems)->toBeArray()->toHaveCount(2)
        ->and($packedItems[0]->xPosition)->toBe(0)
        ->and($packedItems[0]->yPosition)->toBe(0)
        ->and($packedItems[1]->xPosition)->toBe(0)
        ->and($packedItems[1]->yPosition)->toBe(10);
});
//
//it('places complex items', function () {
//    $packer = new Packer();
//
//    $bins = [new Bin(width: 8, height: 8)];
//
//    $items = [
//        new TestItem(width: 1, height: 5, id:"item1"),
//        new TestItem(width: 2, height: 3, id:"item2"),
//        new TestItem(width: 2, height: 3, id:"item3"),
//        new TestItem(width: 2, height: 2, id:"item4"),
//        new TestItem(width: 1, height: 2, id:"item5"),
//    ];
//
//    $packedItems = $packer->pack($bins, $items);
//
//    expect($packedItems)->toBeArray()->toHaveCount(5)
//        ->and($packedItems[0]->xPosition)->toBe(0)->and($packedItems[0]->yPosition)->toBe(0)
//        ->and($packedItems[1]->xPosition)->toBe(0)->and($packedItems[1]->yPosition)->toBe(5)
//        ->and($packedItems[2]->xPosition)->toBe(2)->and($packedItems[2]->yPosition)->toBe(0)
//        ->and($packedItems[3]->xPosition)->toBe(4)->and($packedItems[3]->yPosition)->toBe(0)
//        ->and($packedItems[4]->xPosition)->toBe(6)->and($packedItems[4]->yPosition)->toBe(0)
//    ;
//});
