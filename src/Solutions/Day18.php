<?php

namespace AdventOfCode\Solutions;

use AdventOfCode\Common\Output\TextOutput;
use AdventOfCode\Common\Solution\AbstractSolution;

class Day18 extends AbstractSolution
{
    protected function solvePart1(): int
    {
        return (int) $this->run(1024);
    }

    protected function solvePart2(): string
    {
        $min = 1024;
        $max = 3449;
        $index = null;
        while ($index === null) {
            $avg = (int) (($min + $max) * 0.5);
            if ($this->run($avg) === null) {
                $max = $avg;
            } else {
                $min = $avg;
            }
            if (($max - $min) === 1) {
                $index = $this->run($min) == null ? $min : $max;
            }
        }
        return $this->getInputLines()[$index - 1];
    }
    protected function run($numBytesToProcess): ?int
    {
        $size = 71;
        $target = $size - 1;
        $map = array_fill(0, $size, array_fill(0, $size, true));
        $rainingBytes = $this->getInputLines();
        // echo $numBytesToProcess . ' ';
        for ($b = 0; $b < $numBytesToProcess; ++$b) {
            [$x, $y] = array_map('intval', explode(',', $rainingBytes[$b]));
            $map[$y][$x] = false;
            // echo "[$x, $y]";
        }
        // echo "\n" . TextOutput::map2d($map) . "\n\n";
        $visitedMap = [[0]];
        $dirs = [[0, -1], [1, 0], [0, 1], [-1, 0]];
        $step = 0;
        $points = [[0,0]];
        while ($points) {
            ++$step;
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
        return null;
    }
}
