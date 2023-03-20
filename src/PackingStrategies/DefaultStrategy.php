<?php

declare(strict_types=1);

namespace Hexium\BinPacking\PackingStrategies;

use Hexium\BinPacking\Bin;
use Hexium\BinPacking\BinFactory;
use Hexium\BinPacking\BinResultCollection;
use Hexium\BinPacking\Item;
use Hexium\BinPacking\ItemCannotBePlacedInRemainingBins;
use Hexium\BinPacking\ItemCannotFitInAnyBins;
use Hexium\BinPacking\NodeSorters\LeftNodesFirstSorter;
use Hexium\BinPacking\PackedItem;
use Hexium\BinPacking\PackingStrategy;

class DefaultStrategy implements PackingStrategy
{
    /**
     * @param array<Bin> $bins
     * @param bool $canCreateBin
     * @param BinFactory|null $binFactory
     */
    public function __construct(
        private array $bins = [],
        private readonly bool $canCreateBin = false,
        private readonly ?BinFactory $binFactory = null,
    ) {
        if ($canCreateBin && count($bins) === 0) {
            $this->bins[] = $this->binFactory->create();
        }
    }

    /**
     * @param Item[] $items
     * @throws ItemCannotFitInAnyBins
     * @throws ItemCannotBePlacedInRemainingBins
     */
    public function pack(array $items): BinResultCollection
    {
        $collection = new BinResultCollection();

        foreach ($items as $item) {
            $this->assertItemFitsInAtLeastOneBin($item, $this->bins);
            $hasBeenPlaced = false;

            foreach ($this->bins as $bin) {
                $nodeSorter = new LeftNodesFirstSorter();

                $nodeList = $nodeSorter->sort($bin->nodeList());

                foreach ($nodeList->nodes as $node) {
                    if (!$bin->canFit($item, $node)) {
                        continue;
                    }

                    $bin->placeItem($item, $node->x, $node->y);
                    $collection[$bin]->add(new PackedItem($item, $bin, $node->x, $node->y));
                    $hasBeenPlaced = true;

                    break 2;
                }

                $topRightNode = $bin->nodeOnTopRight();

                // No node suitable for this item, add new node arbitrarily
                if ($topRightNode->x < $bin->width && $bin->canFit($item, $topRightNode)) {
                    $node = $bin->createNodeOnTopRight();
                    $bin->placeItem($item, $node->x, $node->y);
                    $collection[$bin]->add(new PackedItem($item, $bin, $node->x, $node->y));
                    $hasBeenPlaced = true;
                    break;
                }

                // If it cannot fit, we check whether the bin can grow right
                if ($bin->canGrowRight()) {
                    $node = $bin->createNodeOnTopRight();
                    $bin->growRight($item);
                    $bin->placeItem($item, $node->x, $node->y);
                    $collection[$bin]->add(new PackedItem($item, $bin, $node->x, $node->y));
                    $hasBeenPlaced = true;
                    break;
                }
            }

            if (!$hasBeenPlaced && $this->canCreateBin) {
                $newBin = $this->binFactory->create();
                $this->bins[] = $newBin;
                $newBin->placeItem($item, 0, 0);
                $node = $newBin->nodeList()->first();
                $collection[$newBin]->add(new PackedItem($item, $newBin, $node->x, $node->y));
                $hasBeenPlaced = true;
            }

            if (!$hasBeenPlaced) {
                throw new ItemCannotBePlacedInRemainingBins($item, $this->bins);
            }
        }

        return $collection;
    }

    /**
     * @param Item $item
     * @param Bin[] $bins
     * @return void
     * @throws ItemCannotFitInAnyBins
     */
    private function assertItemFitsInAtLeastOneBin(Item $item, array $bins): void
    {
        foreach ($bins as $bin) {
            if ($item->height() <= $bin->height && ($bin->allowExceedWith() || $bin->canGrowRight())) {
                return;
            }

            if ($item->width() <= $bin->width && $bin->allowExceedHeight()) {
                return;
            }

            if ($item->width() <= $bin->width && $item->height() <= $bin->height) {
                return;
            }
        }

        throw new ItemCannotFitInAnyBins($item, $bins);
    }
}
