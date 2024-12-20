<?php

namespace AdventOfCode\Common\Animate\DisplayObject\Common;

use AdventOfCode\Common\Animate\Utils\Transform;
use AdventOfCode\Common\Output\Image\Image;

interface DisplayObject
{
    public function id(): int|string;

    /**
     * @internal
     */
    public function hasId(): bool;

    /**
     * @internal
     */
    public function setId(int|string $id): static;

    public function transform(): Transform;

    public function render(Image $image, Transform $transform): void;
}
