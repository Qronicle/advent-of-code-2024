<?php

namespace AdventOfCode\Solutions;

use AdventOfCode\Common\Solution\AbstractSolution;

class Day11 extends AbstractSolution
{
    protected function solvePart1(): int
    {
        return $this->calculate(25);
    }

    protected function solvePart2(): int
    {
        return $this->calculate(75);
    }

    protected function calculate(int $blinkAmount): int
    {
        // Resolve each of the numbers
        $stones = explode(' ', $this->rawInput);
        $stoneAmounts = [];
        foreach ($stones as $stone) {
            $stoneAmounts[$stone] = ($stoneAmounts[$stone] ?? 0) + 1;
        }
        for ($i = $blinkAmount; $i > 0; --$i) {
            $newStoneAmounts = [];
            foreach ($stoneAmounts as $stone => $amount) {
                if ($stone === 0) {
                    $newStoneAmounts[1] = ($newStoneAmounts[1] ?? 0) + $amount;
                } elseif (strlen($stone) % 2 === 0) {
                    $len = strlen($stone) / 2;
                    $l = substr($stone, 0, $len);
                    $r = (int) substr($stone, $len);
                    $newStoneAmounts[$l] = ($newStoneAmounts[$l] ?? 0) + $amount;
                    $newStoneAmounts[$r] = ($newStoneAmounts[$r] ?? 0) + $amount;
                } else {
                    $m = $stone * 2024;
                    $newStoneAmounts[$m] = ($newStoneAmounts[$m] ?? 0) + $amount;
                }
            }
            $stoneAmounts = $newStoneAmounts;
        }
        return array_sum($stoneAmounts);
    }
}
