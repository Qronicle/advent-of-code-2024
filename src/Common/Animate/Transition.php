<?php

namespace AdventOfCode\Common\Animate;

class Transition
{
    public function __construct(
        public mixed $startValue,
        public mixed $endValue,
        public int $startTime,
        public int $duration,
        public Easing $easing = Easing::LINEAR,
    ) {
    }
}
