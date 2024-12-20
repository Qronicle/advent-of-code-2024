<?php

namespace AdventOfCode\Common\Animate\Utils;

enum Easing
{
    case LINEAR;
    case IN_OUT_SINE;
    case IN_SINE;
    case OUT_SINE;

    public function ease(mixed $start, mixed $end, float $progress): mixed
    {
        $progress = match($this) {
            self::LINEAR => $progress,
            self::IN_SINE => 1 - cos(($progress * pi()) / 2),
            self::OUT_SINE => sin(($progress * pi()) / 2),
            self::IN_OUT_SINE => -(cos(pi() * $progress) - 1) / 2,
        };
        if (is_numeric($start) && is_numeric($end)) {
            return $this->easeNumber($start, $end, $progress);
        }
    }

    public function easeNumber(int|float $start, int|float $end, float $progress): float
    {
        return $start + ($end - $start) * $progress;
    }
}
