<?php

declare(strict_types=1);

namespace Hexium\BinPacking\PackingStrategies;

use Hexium\BinPacking\Bin;
use Hexium\BinPacking\Item;
use Hexium\BinPacking\ItemCannotBePlacedInRemainingBins;
use Hexium\BinPacking\ItemCannotFitInAnyBins;
use Hexium\BinPacking\NodeSorters\LeftNodesFirstSorter;
use Hexium\BinPacking\PackedItem;
use Hexium\BinPacking\PackingStrategy;

class DefaultStrategy implements PackingStrategy
{
    /**
     * @param Bin[] $bins
     * @param Item[] $items
     * @throws ItemCannotFitInAnyBins
     * @throws ItemCannotBePlacedInRemainingBins
     */
    public function pack(array $bins, array $items): array
    {
        $packedItems = [];

        foreach ($items as $item) {
            $this->assertItemFitsInAtLeastOneBin($item, $bins);
            $hasBeenPlaced = false;

            foreach ($bins as $bin) {
                $nodeSorter = new LeftNodesFirstSorter();

                $nodeList = $nodeSorter->sort($bin->nodeList());

                foreach ($nodeList->nodes as $node) {
                    if (!$bin->canFit($item, $node)) {
                        continue;
                    }

                    $bin->placeItem($item, $node->x, $node->y);
                    $packedItems[] = new PackedItem($item, $bin, $node->x, $node->y);
                    $hasBeenPlaced = true;

                    break 2;
                }

                // No node suitable for this item, add new node arbitrarily
                $node = $bin->createNodeOnTopRight();

                if ($bin->canFit($item, $node)) {
                    $bin->placeItem($item, $node->x, $node->y);
                    $packedItems[] = new PackedItem($item, $bin, $node->x, $node->y);
                    $hasBeenPlaced = true;
                }

                // If it cannot fit, we check whether the bin can grow right
                if ($bin->canGrowRight()) {
                    $bin->growRight($item);
                    $bin->placeItem($item, $node->x, $node->y);
                    $packedItems[] = new PackedItem($item, $bin, $node->x, $node->y);
                    $hasBeenPlaced = true;
                }
            }

            if (!$hasBeenPlaced) {
                throw new ItemCannotBePlacedInRemainingBins($item, $bins);
            }
        }

        return $packedItems;
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
            if ($item->width() <= $bin->width && $item->height() <= $bin->height) {
                return;
            }
        }

        throw new ItemCannotFitInAnyBins($item, $bins);
    }
}
