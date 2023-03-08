<?php

declare(strict_types=1);

namespace Hexium\BinPacking;

class NodeList implements \Countable
{
    /**
     * @var Node[]
     */
    public array $nodes;

    public function __construct()
    {
        $this->nodes[] = new Node(0, 0);
    }

    public function count(): int
    {
        return count($this->nodes);
    }

    public function removeAt(int $x, int $y): void
    {
        foreach ($this->nodes as $index => $node) {
            if ($node->x === $x && $node->y === $y) {
                unset($this->nodes[$index]);
                return;
            }
        }

        throw new \OutOfBoundsException("Node not found at {$x}, {$y}");
    }

    public function at(int $x, int $y): Node
    {
        foreach ($this->nodes as $node) {
            if ($node->x === $x && $node->y === $y) {
                return $node;
            }
        }

        throw new \OutOfBoundsException("Node not found at {$x}, {$y}");
    }

    public function removeNodesFrom(int $x, int $y, int $x1, int $height): void
    {
        $this->removeAt($x, $y);

        $x2 = $x + $x1;

        foreach ($this->nodes as $index => $node) {
            if ($node->x >= $x && $node->x < $x2 && $node->y >= $y && $node->y < $y + $height) {
                unset($this->nodes[$index]);
            }
        }
    }
}
