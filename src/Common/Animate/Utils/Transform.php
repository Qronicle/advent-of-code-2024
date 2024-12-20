<?php

namespace AdventOfCode\Common\Animate\Utils;

class Transform
{
    public function __construct(
        public Point2d $offset = new Point2d(),
        public float $scale = 1,
    ) {
    }

    public function add(Transform $transform): static
    {
        return new Transform(
            new Point2d($this->offset->x + $transform->offset->x, $this->offset->y + $transform->offset->y),
            $this->scale * $transform->scale,
        );
    }
}
