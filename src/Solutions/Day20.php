<?php

namespace AdventOfCode\Solutions;

use AdventOfCode\Common\Output\TextOutput;
use AdventOfCode\Common\Solution\AbstractSolution;

class Day20 extends AbstractSolution
{
    protected function solvePart1(): int
    {
        $dirs = [[0, -1], [1, 0], [0, 1], [-1, 0]];
        [$map, $startPos] = $this->prepareMap();

        $shortcuts = 0;
        $pos = $startPos;
        $step = 0;
        while ($pos) {
            $step++;
            $next = null;
            foreach ($dirs as $dir) {
                $x = $pos[0] + $dir[0];
                $y = $pos[1] + $dir[1];
                if ($map[$y][$x] === '#') {
                    $val = $map[$y + $dir[1]][$x + $dir[0]] ?? '#';
                    if (is_int($val)) {
                        $won = $val - ($step + 2);
                        if ($won >= 100) {
                            $shortcuts++;
                        }
                    }
                } elseif ($map[$y][$x] === $step + 1) {
                    $next = [$x, $y];
                }
            }
            $pos = $next;
        }
        return $shortcuts;
    }

    protected function solvePart2(): int
    {
        $dirs = [[0, -1], [1, 0], [0, 1], [-1, 0]];
        [$map, $startPos] = $this->prepareMap();
        $maxMapX = count($map[0]) - 1;
        $maxMapY = count($map) - 1;
        $reach = 20;

        $shortcuts = 0;
        $pos = $startPos;
        $step = 0;
        while ($pos) {
            $step++;
            // Find all shortcuts
            $minY = max(0, $pos[1] - $reach);
            $maxY = min($maxMapY, $pos[1] + $reach);
            for ($y = $minY; $y <= $maxY; ++$y) {
                $horReach = $reach - abs($pos[1] - $y);
                $minX = max(0, $pos[0] - $horReach);
                $maxX = min($maxMapX, $pos[0] + $horReach);
                for ($x = $minX; $x <= $maxX; ++$x) {
                    $dist = abs($x - $pos[0]) + abs($y - $pos[1]);
                    if ($dist < 2) {
                        continue;
                    }
                    $val = $map[$y][$x];
                    if (is_int($val)) {
                        $won = $val - ($step + $dist);
                        if ($won >= 100) {
                            $shortcuts++;
                        }
                    }
                }
            }
            // Find next tile
            $next = null;
            foreach ($dirs as $dir) {
                $x = $pos[0] + $dir[0];
                $y = $pos[1] + $dir[1];
                if ($map[$y][$x] === $step + 1) {
                    $next = [$x, $y];
                }
            }
            $pos = $next;
        }
        return $shortcuts; // 1032760 too high
    }

    protected function prepareMap(): array
    {
        $dirs = [[0, -1], [1, 0], [0, 1], [-1, 0]];
        [$map, $startPos] = $this->getInputMapAndPosition('S');

        // Indicate step count on each tile
        $step = 0;
        $pos = $startPos;
        while ($pos) {
            $map[$pos[1]][$pos[0]] = ++$step;
            foreach ($dirs as $dir) {
                $x = $pos[0] + $dir[0];
                $y = $pos[1] + $dir[1];
                if ($map[$y][$x] === '.') {
                    $pos = [$x, $y];
                    break;
                } elseif ($map[$y][$x] === 'E') {
                    $map[$y][$x] = ++$step;
                    break 2;
                }
            }
        }
        return [$map, $startPos];
    }
}
