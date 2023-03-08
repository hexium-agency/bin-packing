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
}
