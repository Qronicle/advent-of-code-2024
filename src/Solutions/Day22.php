<?php

namespace AdventOfCode\Solutions;

use AdventOfCode\Common\Solution\AbstractSolution;

class Day22 extends AbstractSolution
{
    protected function solvePart1(): int
    {
        $total = 0;
        foreach ($this->getInputLines() as $number) {
            for ($i = 0; $i < 2000; $i++) {
                $number = $this->createNext($number);
            }
            $total += $number;
        }
        return $total;
    }

    protected function solvePart2(): int
    {
        $cache = [];
        foreach ($this->getInputLines() as $monkey => $number) {
            $prev = null;
            $changes = [];
            for ($i = 0; $i < 2000; $i++) {
                $number = $this->createNext($number);
                $price = $number % 10;
                $diff = $price - $prev;
                $changes[] = $diff;
                if ($i > 3) {
                    array_shift($changes);
                    $cache[$changes[0]][$changes[1]][$changes[2]][$changes[3]][$monkey] ??= $price;
                }
                $prev = $price;
            }
        }
        $max = 0;
        // $seq = null;
        foreach ($cache as $a => $bcdm) {
            foreach ($bcdm as $b => $cdm) {
                foreach ($cdm as $c => $dm) {
                    foreach ($dm as $d => $scores) {
                        $total = array_sum($scores);
                        if ($total > $max) {
                            $max = $total;
                            // $seq = [$a, $b, $c, $d];
                        }
                    }
                }
            }
        }
        // dump($seq);
        return $max;
    }

    protected function createNext(int $number): int
    {
        $number = $number ^ ($number * 64) % 16777216;
        $number = $number ^ floor($number / 32) % 16777216;
        return $number ^ ($number * 2048) % 16777216;
    }
}