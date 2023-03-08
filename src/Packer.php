<?php

declare(strict_types=1);

namespace Hexium\BinPacking;

class Packer
{
    /**
     * @throws CannotPackItems
     */
    public function pack(array $bins, array $items): array
    {
        $packedItems = [];

        foreach ($items as $item) {
            foreach ($bins as $bin) {
                $this->ensureItemFitInAtLeastOneBin($item, $bin);

                $packedItems[] = new PackedItem(
                    item: $item,
                    bin: $bin,
                    xPosition: 0,
                    yPosition: 0,
                );
            }
        }

        return $packedItems;
    }

    /**
     * @param mixed $item
     * @param mixed $bin
     * @return void
     * @throws CannotPackItems
     */
    private function ensureItemFitInAtLeastOneBin(mixed $item, mixed $bin): void
    {
        if ($item->width > $bin->width || $item->height > $bin->height) {
            throw new CannotPackItems();
        }
    }
}
