<?php

declare(strict_types=1);

namespace Hexium\BinPacking;

class Bin
{
    /**
     * @var Rectangle[]
     */
    public array $rectangles = [];

    private int $xMax = 0;

    private NodeList $nodeList;

    public function __construct(
        public int $width,
        public int $height,
        private bool $canGrowRight = false,
        private bool $canExceedWidth = false,
        private bool $canExceedHeight = false,
    ) {
        $this->nodeList = new NodeList();
    }

    public function nodeList(): NodeList
    {
        return $this->nodeList;
    }

    public function placeItem(Item $item, int $x, int $y): void
    {
        $this->nodeList->removeNodesFrom($x, $y, $x, $item->height());

        $this->rectangles[] = new Rectangle($x, $y, $item->width(), $item->height());

        $this->xMax = max($this->xMax, $x + $item->width());

        if ($x + $item->width() < $this->width) {
            $this->nodeList->nodes[] = new Node($x + $item->width(), $y);
        }

        if ($y + $item->height() < $this->height) {
            $this->nodeList->nodes[] = new Node($x, $y + $item->height());
        }
    }

    public function canFit(Item $bigItem, Node $node): bool
    {
        $x = $node->x;
        $y = $node->y;

        if (!$this->canExceedWidth && $x + $bigItem->width() > $this->width) {
            return false;
        }

        if (!$this->canExceedHeight && $y + $bigItem->height() > $this->height) {
            return false;
        }

        $x2 = $x + $bigItem->width();
        $y2 = $y + $bigItem->height();

        foreach ($this->rectangles as $rectangle) {
            // Check upper left corner is not inside a rectangle
            if ($rectangle->x <= $x && $rectangle->x + $rectangle->width > $x
                && $rectangle->y <= $y && $rectangle->y + $rectangle->height > $y
            ) {
                return false;
            }

            // Check the bottom right corner is not inside a rectangle
            if ($rectangle->x < $x2 && $rectangle->x + $rectangle->width >= $x2
                && $rectangle->y < $y2 && $rectangle->y + $rectangle->height >= $y2
            ) {
                return false;
            }

            // Check the bottom left corner is not inside a rectangle
            if ($rectangle->x <= $x && $rectangle->x + $rectangle->width > $x
                && $rectangle->y < $y2 && $rectangle->y + $rectangle->height >= $y2
            ) {
                return false;
            }

            // Check the top right corner is not inside a rectangle
            if ($rectangle->x < $x2 && $rectangle->x + $rectangle->width >= $x2
                && $rectangle->y <= $y && $rectangle->y + $rectangle->height > $y
            ) {
                return false;
            }

            // Check the upper edge does not intersect the left side of a rectangle neither the right side
            if ($rectangle->x < $x2 && $rectangle->x + $rectangle->width > $x
                && $rectangle->y <= $y && $rectangle->y + $rectangle->height > $y
            ) {
                return false;
            }

            // Check the bottom edge does not intersect the left side of a rectangle neither the right side
            if ($rectangle->x < $x2 && $rectangle->x + $rectangle->width > $x
                && $rectangle->y < $y2 && $rectangle->y + $rectangle->height >= $y2
            ) {
                return false;
            }

            // Check the left edge does not intersect the top side of a rectangle neither the bottom side
            if ($rectangle->x <= $x && $rectangle->x + $rectangle->width > $x
                && $rectangle->y < $y2 && $rectangle->y + $rectangle->height > $y
            ) {
                return false;
            }

            // Check the right edge does not intersect the top side of a rectangle neither the bottom side
            if ($rectangle->x < $x2 && $rectangle->x + $rectangle->width >= $x2
                && $rectangle->y < $y2 && $rectangle->y + $rectangle->height > $y
            ) {
                return false;
            }
        }

        return true;
    }

    public function createNodeOnTopRight(): Node
    {
        $node = new Node($this->xMax, 0);

        $this->nodeList->nodes[] = $node;

        return $node;
    }

    public function canGrowRight(): bool
    {
        return $this->canGrowRight;
    }

    public function growRight(Item $item): void
    {
        $this->width += $item->width();
    }

    public function cloneEmpty(): Bin
    {
        return new Bin($this->width, $this->height, $this->canGrowRight);
    }

    public function allowExceedWith(): bool
    {
        return $this->canExceedWidth;
    }
}
