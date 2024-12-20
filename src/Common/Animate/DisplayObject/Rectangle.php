<?php

namespace AdventOfCode\Common\Animate\DisplayObject;

use AdventOfCode\Common\Animate\DisplayObject\Common\AbstractDisplayObject;
use AdventOfCode\Common\Animate\Utils\Transform;
use AdventOfCode\Common\Output\Image\Color;
use AdventOfCode\Common\Output\Image\Image;
use AdventOfCode\Common\Output\Image\Stroke;

class Rectangle extends AbstractDisplayObject
{
    public function __construct(
        public float $x,
        public float $y,
        public float $width,
        public float $height,
        public Color $fill,
        public Stroke $stroke,
        public int|string|null $id = null,
    ) {
        parent::__construct(null);
    }

    public function render(Image $image, Transform $transform): void
    {
        $t = $transform->add($this->transform());
        $strokeWidth = $this->stroke->width === -1 ? 1 : ($this->stroke->width * $t->scale);
        if ($strokeWidth) {
            // @todo when fill is (semi) transparent, we'll need to draw the actual lines
            $image->rect(
                x: $t->offset->x + ($this->x * $t->scale),
                y: $t->offset->y + ($this->y * $t->scale),
                width: $this->width * $t->scale,
                height: $this->height * $t->scale,
                fill: $this->stroke->color
            );
            if (($this->width * $t->scale - $strokeWidth * 2) > 0 && ($this->height * $t->scale - $strokeWidth * 2) > 0) {
                $image->rect(
                    x: $t->offset->x + $this->x * $t->scale + $strokeWidth,
                    y: $t->offset->y + $this->y * $t->scale + $strokeWidth,
                    width: $this->width * $t->scale - $strokeWidth * 2,
                    height: $this->height * $t->scale - $strokeWidth * 2,
                    fill: $this->fill
                );
            }
        } else {
            $image->rect(
                x: $t->offset->x + ($this->x * $t->scale),
                y: $t->offset->y + ($this->y * $t->scale),
                width: $this->width * $t->scale,
                height: $this->height * $t->scale,
                fill: $this->fill
            );
        }
    }
}
