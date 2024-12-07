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
            $numbers = array_map('intval', explode(' ', $n));
            $solutions = [array_shift($numbers) => true];
            foreach ($numbers as $number) {
                $newSolutions = [];
                foreach ($solutions as $solution => $tmp) {
                    if ($solution > $target) {
                        continue;
                    }
                    $newSolutions[$solution + $number] = true;
                    $newSolutions[$solution * $number] = true;
                }
                $solutions = $newSolutions;
            }
            if (isset($solutions[$target])) {
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
            $numbers = array_map('intval', explode(' ', $n));
            $solutions = [array_shift($numbers) => true];
            foreach ($numbers as $number) {
                $newSolutions = [];
                foreach ($solutions as $solution => $tmp) {
                    if ($solution > $target) {
                        continue;
                    }
                    $newSolutions[$solution + $number] = true;
                    $newSolutions[$solution * $number] = true;
                    $newSolutions[$solution . $number] = true;
                }
                $solutions = $newSolutions;
            }
            if (isset($solutions[$target])) {
                $total += $target;
            }
        }
        return $total;
    }
}
