<?php

namespace AdventOfCode\Common\Output\Image;

use GdImage;

class Image
{
    protected GdImage $image;

    /** @var array<string, int> */
    protected array $colors;

    public function __construct(
        public readonly int $width,
        public readonly int $height,
        ?Color $background = null,
    ) {
        $this->image = imagecreatetruecolor($width, $height);
        $this->rect(0, 0, $width, $height, $background ?? Color::rgb(0,0,0));
    }

    public function rect(int|float $x, int|float $y, int|float $width, int|float $height, Color $fill): static
    {
        $r = imagefilledrectangle(
            $this->image,
            round($x),
            round($y),
            round($x) + round($width) - 1,
            round($y) + round($height) - 1,
            $this->getGdColor($fill),
        );
        if (!$r) {
            dump('no');
        }
        return $this;
    }

    public function png(string $path): static
    {
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), recursive: true);
        }
        imagepng($this->image, $path);
        return $this;
    }

    protected function getGdColor(Color $color): int
    {
        if (!isset($this->colors[(string) $color])) {
            if ($color->a < 1) {
                $alpha = (int) ((1 - $color->a) * 127);
                dump($alpha);
                $gdColor = imagecolorallocatealpha($this->image, $color->r, $color->g, $color->b, $alpha);
            } else {
                $gdColor = imagecolorallocate($this->image, $color->r, $color->g, $color->b);
            }
            if ($gdColor === false) {
                throw new \RuntimeException("Invalid color '$color' requested");
            }
            $this->colors[(string) $color] = $gdColor;
        }
        return $this->colors[(string) $color];
    }
}
