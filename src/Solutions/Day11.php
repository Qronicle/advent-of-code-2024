<?php

namespace AdventOfCode\Solutions;

use AdventOfCode\Common\Solution\AbstractSolution;

class Day11 extends AbstractSolution
{
    protected function solvePart1(): int
    {
        return $this->calculate(25);
    }

    protected function solvePart2(): int
    {
        return $this->calculate(75);
    }

    protected function calculate(int $blinkAmount): int
    {
        // Generate a cache that we can easily reuse for the base numbers
        $stoneTotals = [[1, 1]];
        $endStones = [[1 => 1]];
        for ($i = 1; $i < 10; $i++) {
            [$nums, $end] = $this->createBaseTree($i);
            $stoneTotals[$i] = $nums;
            $endStones[$i] = $end;
        }
        $stoneTotals[16192] = [1, 1, 2, 4, 7];
        $endStones[16192] = $endStones[8];
        foreach ($endStones as $stone => $tmp) {
            $stoneTotals[$stone] = $this->completeSimulation($stone, $blinkAmount, $stoneTotals, $endStones);
        }

        // Resolve each of the numbers
        $stones = explode(' ', $this->rawInput);
        $total = 0;
        foreach ($stones as $stone) {
            $total += $this->blink($stone, $blinkAmount, $stoneTotals);
        }
        return $total;
    }

    protected function blink(string $stone, int $amount, array $stoneTotals): int
    {
        if ($amount === 0) {
            return 1;
        }
        if (isset($stoneTotals[$stone][$amount])) {
            return $stoneTotals[$stone][$amount];
        }
        if ($stone === '0') {
            return $this->blink(1, $amount - 1, $stoneTotals);
        }
        if (strlen($stone) % 2 === 0) {
            $len = strlen($stone) / 2;
            return $this->blink(substr($stone, 0, $len), $amount - 1, $stoneTotals)
                + $this->blink((int) substr($stone, $len), $amount - 1, $stoneTotals);
        }
        return $this->blink($stone * 2024, $amount - 1, $stoneTotals);
    }

    protected function createBaseTree(int $stone): array
    {
        $steps = [1, 1];
        $stone *= 2024;
        if (strlen($stone) % 2 !== 0) {
            $stone *= 2024;
            $steps[] = 1;
            $steps[] = 2;
            $steps[] = 4;
            $steps[] = 8;
        } else {
            $steps[] = 2;
            $steps[] = 4;
        }
        $endNumbers = str_split($stone);
        $result = [];
        foreach ($endNumbers as $number) {
            if (isset($result[$number])) {
                $result[$number]++;
            } else {
                $result[$number] = 1;
            }
        }
        if ($stone === 32772608) {
            $steps[5]--;
            unset($result[0], $result[8]);
            $result[16192] = 1;
        }
        return [$steps, $result];
    }

    protected function completeSimulation(int $stone, int $steps, array $stoneTotals, array $endStones): array
    {
        $newStoneTotals = $stoneTotals[$stone];
        $startStep = count($stoneTotals[$stone]) - 1;
        $nextSteps = [$startStep => $endStones[$stone]];
        while ($nextSteps) {
            $newNextSteps = [];
            foreach ($nextSteps as $startStep => $stones) {
                foreach ($stones as $s => $mul) {
                    $endStep = min(count($stoneTotals[$s]) - 1, $steps - $startStep);
                    for ($step = 1; $step <= $endStep; ++$step) {
                        $newStoneTotals[$startStep + $step] = ($newStoneTotals[$startStep + $step] ?? 0) + ($mul * $stoneTotals[$s][$step]);
                    }
                    if ($startStep + $endStep < $steps) {
                        foreach ($endStones[$s] as $nextStone => $nextMul) {
                            if (!isset($newNextSteps[$startStep + $endStep][$nextStone])) {
                                $newNextSteps[$startStep + $endStep][$nextStone] = $mul * $nextMul;
                            } else {
                                $newNextSteps[$startStep + $endStep][$nextStone] += $mul * $nextMul;
                            }
                        }
                    }
                }
            }
            $nextSteps = $newNextSteps;
        }
        return $newStoneTotals;
    }
}
