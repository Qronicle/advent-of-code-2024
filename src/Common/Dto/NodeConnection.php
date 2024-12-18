<?php

namespace AdventOfCode\Common\Dto;

class NodeConnection
{
    public function __construct(
        public MapNode $node,
        public int $distance,
    ) {
    }
}
