<?php

namespace AdventOfCode\Solutions;

use AdventOfCode\Common\Solution\AbstractSolution;

class Day13 extends AbstractSolution
{
    protected function solvePart1(): int
    {
        $games = explode("\n\n", $this->rawInput);
        $total = 0;
        foreach ($games as $game) {
            [$aDesc, $bDesc, $targetDesc] = explode("\n", $game);
            preg_match('/X\+(\d+), Y\+(\d+)/', $aDesc, $aMatches);
            preg_match('/X\+(\d+), Y\+(\d+)/', $bDesc, $bMatches);
            preg_match('/X=(\d+), Y=(\d+)/', $targetDesc, $targetMatches);
            [,$aX,$aY] = array_map('intval', $aMatches);
            [,$bX,$bY] = array_map('intval', $bMatches);
            [,$targetX,$targetY] = array_map('intval', $targetMatches);
            $x = 0;
            $y = 0;
            $a = 0;
            $tokens = PHP_INT_MAX;
            do {
                if ($x === $targetX && $y === $targetY) {
                    $tokens = min($a * 3, $tokens);
                }
                if (($targetX - $x) % $bX === 0 && ($targetY - $y) % $bY === 0) {
                    $xPress = ($targetX - $x) / $bX;
                    $yPress = ($targetY - $y) / $bY;
                    if ($xPress === $yPress) {
                        $tokens = min($tokens, $a * 3 + $xPress);
                    }
                }
                // Push A!
                $x += $aX;
                $y += $aY;
            } while ($x <= $targetX && $y <= $targetY && ++$a < 100);
            if ($tokens < PHP_INT_MAX) {
                $total += $tokens;
            }
        }
        return $total; // 38876 = too high
    }

    protected function solvePart2(): int
    {
        return ':(';
    }
}
