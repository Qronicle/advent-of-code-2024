<?php

namespace AdventOfCode\Solutions;

use AdventOfCode\Common\Solution\AbstractSolution;

class Day11 extends AbstractSolution
{
    protected function solvePart1(): int
    {
        $stones = explode(' ', $this->rawInput);
        $total = 0;
        foreach ($stones as $stone) {
            $total += $this->blink($stone, 25);
        }
        return $total;
    }

    protected function blink(string $stone, int $n): int
    {
        if ($n === 0) {
            return 1;
        }
        if ($stone === '0') {
            return $this->blink(1, $n - 1);
        }
        if (strlen($stone) % 2 === 0) {
            $len = strlen($stone) / 2;
            return $this->blink(substr($stone, 0, $len), $n - 1)
                + $this->blink((int) substr($stone, $len), $n - 1);
        }
        return $this->blink($stone * 2024, $n - 1);
    }

    protected function solvePart2(): int
    {
        return ':(';
    }
}
