<?php

namespace AdventOfCode\Solutions;

use AdventOfCode\Common\Solution\AbstractSolution;

class Day02 extends AbstractSolution
{
    protected function solvePart1(): int
    {
        $numSafe = 0;
        foreach ($this->getInputLines() as $report) {
            $levels = explode(' ', $report);
            $numSafe += (int) $this->isReportSafe($levels);
        }
        return $numSafe;
    }

    protected function solvePart2(): int
    {
        $numSafe = 0;
        foreach ($this->getInputLines() as $report) {
            $levels = explode(' ', $report);
            $numSafe += (int) $this->isReportSafe($levels, 1);
        }
        return $numSafe;
    }

    protected function isReportSafe(array $levels, int $tolerance = 0): bool
    {
        $safe = true;
        $inc = $dec = false;
        for ($i = 1; $i < count($levels); ++$i) {
            $diff = $levels[$i] - $levels[$i - 1];
            $diff < 0 ? $inc = true : ($diff > 0 ? $dec = true : $inc = $dec = true);
            if (($inc && $dec) || $diff < -3 || $diff > 3) {
                if ($tolerance) {
                    for ($ii = max(0, $i - 2); $ii <= $i; ++$ii) {
                        $altLevels = $levels;
                        array_splice($altLevels, $ii, 1);
                        if ($this->isReportSafe($altLevels)) {
                            return true;
                        }
                    }
                    return false;
                } else {
                    $safe = false;
                    break;
                }
            }
        }
        return $safe;
    }
}
