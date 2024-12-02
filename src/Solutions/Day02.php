<?php

namespace AdventOfCode\Solutions;

use AdventOfCode\Common\Solution\AbstractSolution;

class Day02 extends AbstractSolution
{
    protected function solvePart1(): int
    {
        $numSafe = 0;
        foreach ($this->getInputLines() as $l => $report) {
            $levels = explode(' ', $report);
            $safe = true;
            $inc = $dec = false;
            for ($i = 1; $i < count($levels); ++$i) {
                $diff = $levels[$i] - $levels[$i - 1];
                $diff < 0 ? $inc = true : ($diff > 0 ? $dec = true : $inc = $dec = true);
                if (($inc && $dec) || $diff < -3 || $diff > 3) {
                    $safe = false;
                    break;
                }
            }
            $numSafe += (int) $safe;
        }
        return $numSafe;
    }

    protected function solvePart2(): string
    {
        return ':(';
    }
}
