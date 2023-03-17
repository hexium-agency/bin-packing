<?php

declare(strict_types=1);

namespace Hexium\BinPacking;

use Hexium\BinPacking\PackingStrategies\DefaultStrategy;

class Packer
{
    private PackingStrategy $packingStrategy;

    public function __construct(?PackingStrategy $packingStrategy = null)
    {
        $this->packingStrategy = $packingStrategy ?? new DefaultStrategy();
    }

    /**
     * @param array<Item> $items
     * @throws ItemCannotBePlacedInRemainingBins|ItemCannotFitInAnyBins
     */
    public function pack(array $items): BinResultCollection
    {
        return $this->packingStrategy->pack($items);
    }
}
