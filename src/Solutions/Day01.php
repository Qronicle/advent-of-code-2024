<?php

namespace AdventOfCode\Solutions;

use AdventOfCode\Common\Solution\AbstractSolution;

class Day01 extends AbstractSolution
{
    protected function solvePart1(): int
    {
        $ids = [];
        foreach ($this->getInputLines() as $line) {
            $newIds = explode('   ', $line);
            $ids[0][] = (int) $newIds[0];
            $ids[1][] = (int) $newIds[1];
        }
        sort($ids[0]);
        sort($ids[1]);
        $dist = 0;
        for ($i = 0; $i < count($ids[0]); ++$i) {
            $dist += abs($ids[0][$i] - $ids[1][$i]);
        }
        return $dist;
    }

    protected function solvePart2(): string
    {
        return ':(';
    }
}
