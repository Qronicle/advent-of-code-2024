<?php

namespace AdventOfCode\Common\Dto;

class MapNode
{
    public string $id;

    /**
     * @var NodeConnection[]
     */
    public array $connections = [];

    public function __construct(
        public int $x,
        public int $y,
        public bool $deletable = true,
    ) {
        $this->id = "$x,$y";
    }
}
