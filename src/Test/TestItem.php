<?php

declare(strict_types=1);

namespace Hexium\BinPacking\Test;

use Hexium\BinPacking\Item;

readonly class TestItem implements Item
{
    public function __construct(private int $width, private int $height, private string $id)
    {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function width(): int
    {
        return $this->width;
    }

    public function height(): int
    {
        return $this->height;
    }

    public function area(): int
    {
        return $this->width * $this->height;
    }
}
