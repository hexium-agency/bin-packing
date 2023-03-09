<?php

declare(strict_types=1);

namespace Hexium\BinPacking\NodeSorters;

use Hexium\BinPacking\Node;
use Hexium\BinPacking\NodeList;
use Hexium\BinPacking\NodeSorter;

class LeftNodesFirstSorter implements NodeSorter
{
    public function sort(NodeList $nodes): NodeList
    {
        $sortedNodes = $nodes->nodes;

        usort($sortedNodes, static fn(Node $a, Node $b) => $a->x <=> $b->x);

        return NodeList::fromList($sortedNodes);
    }
}
