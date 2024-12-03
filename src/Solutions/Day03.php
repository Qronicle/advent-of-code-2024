<?php

namespace AdventOfCode\Solutions;

use AdventOfCode\Common\Solution\AbstractSolution;

class Day03 extends AbstractSolution
{
    protected function solvePart1(): int
    {
        preg_match_all('/mul\((\d+),(\d+)\)/', $this->rawInput, $matches);
        [, $lefts, $rights] = $matches;
        $total = 0;
        foreach ($lefts as $i => $left) {
            $right = $rights[$i];
            $total += $left * $right;
        }
        return $total;
    }

    protected function solvePart2(): string
    {
        preg_match_all('/(do(n\'t)?)|mul\((\d+),(\d+)\)/', $this->rawInput, $matches);
        [$completes, , , $lefts, $rights] = $matches;
        $enabled = true;
        $total = 0;
        foreach ($completes as $i => $complete) {
            switch ($complete) {
                case 'do':
                    $enabled = true;
                    break;
                case 'don\'t':
                    $enabled = false;
                    break;
                default:
                    if ($enabled) {
                        $total += $lefts[$i] * $rights[$i];
                    }
            }
        }
        return $total;
    }
}
