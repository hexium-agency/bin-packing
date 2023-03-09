<?php

declare(strict_types=1);

namespace Hexium\BinPacking;

class Bin
{
    /**
     * @var Rectangle[]
     */
    public array $rectangles = [];

    private NodeList $nodeList;

    public function __construct(public int $width, public int $height)
    {
        $this->nodeList = new NodeList();
    }

    public function nodeList(): NodeList
    {
        return $this->nodeList;
    }

    public function placeItem(Test\TestItem $item, int $x, int $y): void
    {
        $this->nodeList->removeNodesFrom($x, $y, $x, $item->height());

        $this->rectangles[] = new Rectangle($x, $y, $item->width(), $item->height());

        if ($x + $item->width() < $this->width) {
            $this->nodeList->nodes[] = new Node($x + $item->width(), $y);
        }

        if ($y + $item->height() < $this->height) {
            $this->nodeList->nodes[] = new Node($x, $y + $item->height());
        }
    }

    public function canFit(Test\TestItem $bigItem, Node $node): bool
    {
        $x = $node->x;
        $y = $node->y;

        if ($x + $bigItem->width() > $this->width) {
            return false;
        }

        if ($y + $bigItem->height() > $this->height) {
            return false;
        }

        $x2 = $x + $bigItem->width();
        $y2 = $y + $bigItem->height();

        foreach ($this->rectangles as $rectangle) {
            // Check the node is not inside a rectangle
            if ($rectangle->x <= $x && $rectangle->x + $rectangle->width > $x
                && $rectangle->y <= $y && $rectangle->y + $rectangle->height > $y
            ) {
                return false;
            }

            // Check the end of item is not inside a rectangle
            if ($rectangle->x < $x2 && $rectangle->x + $rectangle->width >= $x2
                && $rectangle->y < $y2 && $rectangle->y + $rectangle->height >= $y2
            ) {
                return false;
            }
        }

        return true;
    }
}
