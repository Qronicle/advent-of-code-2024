<?php

namespace AdventOfCode\Common\Output\Image;

class Stroke
{
    public function __construct(
        public Color $color,
        public int $width,
    ) {
    }

    public static function hairline(Color $color): static
    {
        return new Stroke($color, -1);
    }

    public static function none(): static
    {
        return new Stroke(Color::black(), 0);
    }
}
