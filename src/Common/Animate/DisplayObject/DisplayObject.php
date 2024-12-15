<?php

namespace AdventOfCode\Common\Animate\DisplayObject;

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

    public function render(Image $image, int $scale): void;
}
