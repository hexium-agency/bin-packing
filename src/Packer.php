<?php

declare(strict_types=1);

namespace Hexium\BinPacking;

use Hexium\BinPacking\NodeSorters\LeftNodesFirstSorter;

class Packer
{
    /**
     * @param array<Bin> $bins
     * @param array<Item> $items
     * @throws CannotPackItems
     */
    public function pack(array $bins, array $items): array
    {
        $packedItems = [];

        foreach ($items as $item) {
            foreach ($bins as $bin) {
                $this->assertItemFitsInAtLeastOneBin($item, $bin);

                $nodeSorter = new LeftNodesFirstSorter();

                $nodeList = $nodeSorter->sort($bin->nodeList());

                foreach ($nodeList->nodes as $node) {
                    if (!$bin->canFit($item, $node)) {
                        continue;
                    }

                    $bin->placeItem($item, $node->x, $node->y);
                    $packedItems[] = new PackedItem($item, $bin, $node->x, $node->y);

                    break;
                }
            }
        }

        return $packedItems;
    }

    /**
     * @param Item $item
     * @param Bin $bin
     * @return void
     * @throws CannotPackItems
     */
    private function assertItemFitsInAtLeastOneBin(Item $item, Bin $bin): void
    {
        if ($item->width() > $bin->width || $item->height() > $bin->height) {
            throw new CannotPackItems();
        }
    }
}
