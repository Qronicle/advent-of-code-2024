<?php

namespace AdventOfCode\Common\Animate\DisplayObject\Common;

use AdventOfCode\Common\Animate\Utils\Transform;
use AdventOfCode\Common\Output\Image\Image;

abstract class AbstractDisplayObject implements DisplayObject
{
    private int|string $id;
    private Transform $transform;

    public function __construct(
        int|string|null $id,
    ) {
        if ($id !== null) {
            $this->id = $id;
        }
        $this->transform = new Transform();
    }

    public function id(): int|string
    {
        return $this->id;
    }

    public function hasId(): bool
    {
        return isset($this->id);
    }

    public function setId(int|string $id): static
    {
        if (isset($this->id)) {
            throw new \LogicException('Cannot update id after instantiation');
        }
        $this->id = $id;
        return $this;
    }

    public function transform(): Transform
    {
        return $this->transform;
    }

    abstract public function render(Image $image, Transform $transform): void;
}
