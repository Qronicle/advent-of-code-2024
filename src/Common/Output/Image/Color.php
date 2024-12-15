<?php

namespace AdventOfCode\Common\Output\Image;

class Color
{
    public function __construct(
        public int $r,
        public int $g,
        public int $b,
    ) {
    }

    public function __toString(): string
    {
        return "rgb($this->r,$this->g,$this->b)";
    }

    public static function rgb(int $r, int $g, int $b): static
    {
        return new Color($r, $g, $b);
    }

    public static function hex(string $hexCode): static
    {
        if (strlen($hexCode) === 4) {
            $hexCode = $hexCode[0] . $hexCode[1] . $hexCode[1] . $hexCode[2] . $hexCode[2] . $hexCode[3] . $hexCode[3];
        }
        [$r, $g, $b] = sscanf($hexCode, "#%02x%02x%02x");
        return new Color($r, $g, $b);
    }

    public static function white(): static
    {
        return new Color(255, 255, 255);
    }

    public static function black(): static
    {
        return new Color(0, 0, 0);
    }
}
