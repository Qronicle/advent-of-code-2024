<?php

namespace AdventOfCode\Solutions;

use AdventOfCode\Common\Output\TextOutput;
use AdventOfCode\Common\Solution\AbstractSolution;

class Day20 extends AbstractSolution
{
    protected function solvePart1(): int
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

        // Find shortcuts
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
        return ':(';
    }
}
