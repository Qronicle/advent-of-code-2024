<?php

namespace AdventOfCode\Common\Animate\DisplayObject;

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

    public function render(Image $image, int $scale): void
    {
        $strokeWidth = $this->stroke->width === -1 ? 1 : ($this->stroke->width * $scale);
        if ($strokeWidth) {
            $image->rect($this->x * $scale, $this->y * $scale, $this->width * $scale, $this->height * $scale, $this->stroke->color);
            if (($this->width * $scale - $strokeWidth * 2) > 0 && ($this->height * $scale - $strokeWidth * 2) > 0) {
                $image->rect(
                    x: $this->x * $scale + $strokeWidth,
                    y: $this->y * $scale + $strokeWidth,
                    width: $this->width * $scale - $strokeWidth * 2,
                    height: $this->height * $scale - $strokeWidth * 2,
                    fill: $this->fill
                );
            }
        } else {
            $image->rect($this->x * $scale, $this->y * $scale, $this->width * $scale, $this->height * $scale, $this->fill);
        }
    }
}
