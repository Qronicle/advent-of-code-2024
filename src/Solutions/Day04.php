<?php

namespace AdventOfCode\Solutions;

use AdventOfCode\Common\Solution\AbstractSolution;
use AdventOfCode\Common\Utils\MapUtils;

class Day04 extends AbstractSolution
{
    protected function solvePart1(): int
    {
        $map = $this->getInputMap();
        $dirs = [[0, 1], [1, 1], [1, 0], [1, -1], [0, -1], [-1, -1], [-1, 0], [-1, 1]];
        $search = [1 => 'M', 'A', 'S'];

        $matches = 0;
        foreach ($map as $y => $row) {
            foreach ($row as $x => $char) {
                if ($char === 'X') {
                    foreach ($dirs as $dir) {
                        $match = 1;
                        foreach ($search as $offset => $validChar) {
                            $char = $map[$y + $dir[1] * $offset][$x + $dir[0] * $offset] ?? null;
                            if ($char !== $validChar) {
                                $match = 0;
                                break;
                            }
                        }
                        $matches += $match;
                    }
                }
            }
        }
        return $matches;
    }

    protected function solvePart2(): string
    {
        $map = $this->getInputMap();

        $matches = 0;
        foreach ($map as $y => $row) {
            foreach ($row as $x => $char) {
                if ($char === 'A') {
                    $w1 = ($map[$y + 1][$x - 1] ?? '') . ($map[$y - 1][$x + 1] ?? '');
                    $w2 = ($map[$y + 1][$x + 1] ?? '') . ($map[$y - 1][$x - 1] ?? '');
                    if (($w1 === 'MS' || $w1 === 'SM') && ($w2 === 'MS' || $w2 === 'SM')) {
                        ++$matches;
                    }
                }
            }
        }
        return $matches;
    }
}
