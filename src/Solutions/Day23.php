<?php

namespace AdventOfCode\Solutions;

use AdventOfCode\Common\Solution\AbstractSolution;

class Day23 extends AbstractSolution
{
    protected function solvePart1(): int
    {
        $connections = [];
        foreach ($this->getInputLines() as $line) {
            [$from, $to] = explode('-', $line);
            $connections[$from][$to] = true;
            $connections[$to][$from] = true;
        }
        $threes = [];
        foreach ($connections as $from => $tos) {
            if ($tos < 2) {
                continue;
            }
            foreach ($tos as $to => $tmp) {
                $thirds = $connections[$to];
                if (count($thirds) < 2) {
                    continue;
                }
                foreach ($thirds as $third => $tmp2) {
                    if (isset($connections[$from][$third])) {
                        $three = [$from, $to, $third];
                        sort($three);
                        $threes[implode(',', $three)] = $three;
                    }
                }
            }
        }
        $total = 0;
        foreach ($threes as [$a, $b, $c]) {
            $total += (int) ($a[0] === 't' || $b[0] === 't' || $c[0] === 't');
        }
        return $total;
    }

    protected function solvePart2(): string
    {
        $connections = [];
        foreach ($this->getInputLines() as $line) {
            [$from, $to] = explode('-', $line);
            $connections[$from][$to] = true;
            $connections[$to][$from] = true;
        }

        $largestGroup = [];
        foreach ($connections as $from => $tos) {
            $set = $this->reduceGroup($connections, $tos);
            $group = array_keys($set);
            $group[] = $from;

            if (count($group) > count($largestGroup)) {
                $largestGroup = $group;
            }
        }
        sort($largestGroup);
        return join(',', $largestGroup);
    }

    protected function reduceGroup(array $connections, array $set): array
    {
        foreach ($set as $b => $tmp) {
            foreach ($set as $c => $tmp2) {
                if ($b === $c) {
                    continue;
                }
                if (!isset($connections[$b][$c])) {
                    $setA = $set;
                    unset($setA[$b]);
                    $left = $this->reduceGroup($connections, $setA);

                    $setB = $set;
                    unset($setB[$c]);
                    $right = $this->reduceGroup($connections, $setB);

                    return count($left) > count($right) ? $left : $right;
                }
            }
        }

        return $set;

    }
}
