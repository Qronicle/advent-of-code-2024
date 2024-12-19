<?php

namespace AdventOfCode\Solutions;

use AdventOfCode\Common\Output\TextOutput;
use AdventOfCode\Common\Solution\AbstractSolution;

class Day18 extends AbstractSolution
{
    protected function solvePart1(): int
    {
        $size = 71;
        $toProcess = 1024;
        $target = $size - 1;
        $map = array_fill(0, $size, array_fill(0, $size, true));
        $rainingBytes = $this->getInputLines();
        for ($b = 0; $b < $toProcess; ++$b) {
            [$x, $y] = array_map('intval', explode(',', $rainingBytes[$b]));
            $map[$y][$x] = false;
        }
        $visitedMap = [[0]];
        $dirs = [[0, -1], [1, 0], [0, 1], [-1, 0]];
        $step = 0;
        $points = [[0,0]];
        while (++$step) {
            $newPoints = [];
            foreach ($points as [$x, $y]) {
                foreach ($dirs as [$dx, $dy]) {
                    $nx = $x + $dx;
                    $ny = $y + $dy;
                    if (!($map[$ny][$nx] ?? null)) {
                        continue;
                    }
                    if (isset($visitedMap[$ny][$nx])) {
                        continue;
                    }
                    if ($nx === $target && $ny === $target) {
                        return $step;
                    }
                    $visitedMap[$ny][$nx] = $step;
                    $newPoints[] = [$nx, $ny];
                }
            }
            $points = $newPoints;
        }
    }

    protected function solvePart2(): int
    {
        return ':(';
    }
}
