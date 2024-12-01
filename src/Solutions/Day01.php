<?php

namespace AdventOfCode\Solutions;

use AdventOfCode\Common\Solution\AbstractSolution;

class Day01 extends AbstractSolution
{
    protected function solvePart1(): int
    {
        $ids = $this->getIds();
        sort($ids[0]);
        sort($ids[1]);
        $dist = 0;
        for ($i = 0; $i < count($ids[0]); ++$i) {
            $dist += abs($ids[0][$i] - $ids[1][$i]);
        }
        return $dist;
    }

    protected function solvePart2(): int
    {
        [$list1, $list2] = $this->getIds();
        $list2Counts = [];
        foreach ($list2 as $num) {
            $list2Counts[$num] = isset($list2Counts[$num]) ? $list2Counts[$num] + 1 : 1;
        }
        $total = 0;
        foreach ($list1 as $num) {
            $total += $num * ($list2Counts[$num] ?? 0);
        }
        return $total;
    }

    protected function getIds(): array
    {
        $ids = [];
        foreach ($this->getInputLines() as $line) {
            $newIds = explode('   ', $line);
            $ids[0][] = (int) $newIds[0];
            $ids[1][] = (int) $newIds[1];
        }
        return $ids;
    }
}
