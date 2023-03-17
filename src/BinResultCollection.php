<?php

declare(strict_types=1);

namespace Hexium\BinPacking;

use Traversable;

class BinResultCollection implements \Countable, \ArrayAccess, \IteratorAggregate
{
    /**
     * @var BinResult[]
     */
    public array $bins = [];

    public function add(BinResult $bin): void
    {
        $this->bins[] = $bin;
    }

    public function count(): int
    {
        return count($this->bins);
    }

    public function offsetExists(mixed $offset): bool
    {
        // TODO: Implement offsetExists() method.
    }

    /**
     * @param Bin $offset
     * @return BinResult
     */
    public function offsetGet(mixed $offset): BinResult
    {
        foreach ($this->bins as $binResult) {
            if ($binResult->bin === $offset) {
                return $binResult;
            }
        }

        $binResult = new BinResult($offset);

        $this->bins[] = $binResult;

        return $binResult;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        // TODO: Implement offsetSet() method.
    }

    public function offsetUnset(mixed $offset): void
    {
        // TODO: Implement offsetUnset() method.
    }

    public function first(): BinResult
    {
        return $this->bins[0];
    }

    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->bins);
    }
}
