<?php

namespace AdventOfCode\Solutions;

use AdventOfCode\Common\Solution\AbstractSolution;

class Day07 extends AbstractSolution
{
    protected function solvePart1(): int
    {
        $total = 0;
        foreach ($this->getInputLines() as $line) {
            [$target, $n] = explode(': ', $line);
            $target = (int) $target;
            $numbers = array_map('intval', array_reverse(explode(' ', $n)));
            $solutions = [$target => true];
            foreach ($numbers as $number) {
                $newSolutions = [];
                foreach ($solutions as $solution => $tmp) {
                    if (($add = $solution - $number) >= 0) {
                        $newSolutions[$add] = true;
                    }
                    if ($solution % $number === 0) {
                        $newSolutions[$solution / $number] = true;
                    }
                }
                $solutions = $newSolutions;
            }
            if (isset($solutions[0])) {
                $total += $target;
            }
        }
        return $total;
    }

    protected function solvePart2(): int
    {
        $total = 0;
        foreach ($this->getInputLines() as $line) {
            [$target, $n] = explode(': ', $line);
            $target = (int) $target;
            $numbers = array_map('intval', array_reverse(explode(' ', $n)));
            $solutions = [$target => true];
            foreach ($numbers as $number) {
                $newSolutions = [];
                foreach ($solutions as $solution => $tmp) {
                    if (($add = $solution - $number) >= 0) {
                        $newSolutions[$add] = true;
                    }
                    if ($solution % $number === 0) {
                        $newSolutions[$solution / $number] = true;
                    }
                    if (str_ends_with($solution, $number)) {
                        $newSolutions[substr($solution, 0, -strlen($number)) ?: 0] = true;
                    }
                }
                $solutions = $newSolutions;
            }
            if (isset($solutions[0])) {
                $total += $target;
            }
        }
        return $total;
    }
}
