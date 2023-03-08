<?php

declare(strict_types=1);

namespace Hexium\BinPacking;

interface NodeSorter
{
    public function sort(NodeList $nodes): NodeList;
}
