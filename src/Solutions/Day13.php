<?php

namespace AdventOfCode\Solutions;

use AdventOfCode\Common\Solution\AbstractSolution;

/**
 * 8400 = 94x + 22y
 * 5400 = 34x + 67y
 *
 * x = (8400 - 22y) / 94
 * y = (5400 - 34x) / 67
 *
 * x = (8400 - 22 * (5400 - 34x) / 67) / 94
 * x = (8400 - (118800 - 748x) / 67) / 94
 * x = (8400 - 1773 + 11x) / 94
 * x = 6627/94 + 11x/94
 * x = 70.5 + 11x/94
 * x - 11x/94 = 70.5
 * (94x - 11x)/94 = 70.5
 * 83x = 6627
 * x = 6627/83
 * x = 80
 *
 * replacement w variables:
 * https://www.wolframalpha.com/input?i2d=true&i=solve+A*Subscript%5Bx%2Ca%5D%2BB*Subscript%5Bx%2Cb%5D%3DSubscript%5Bx%2Ct%5D+and+A*Subscript%5By%2Ca%5D%2BB*Subscript%5By%2Cb%5D%3DSubscript%5By%2Ct%5D+for+A+and+B
 */
class Day13 extends AbstractSolution
{
    protected function solvePart1(): int
    {
        return $this->calculate();
    }

    protected function solvePart2(): int
    {
        return $this->calculate(10000000000000);
    }

    protected function calculate(int $offset = 0)
    {
        $games = explode("\n\n", $this->rawInput);
        $total = 0;
        foreach ($games as $game) {
            [$aDesc, $bDesc, $targetDesc] = explode("\n", $game);
            preg_match('/X\+(\d+), Y\+(\d+)/', $aDesc, $aMatches);
            preg_match('/X\+(\d+), Y\+(\d+)/', $bDesc, $bMatches);
            preg_match('/X=(\d+), Y=(\d+)/', $targetDesc, $targetMatches);
            [,$Xa,$Ya] = array_map('intval', $aMatches);
            [,$Xb,$Yb] = array_map('intval', $bMatches);
            [,$Xt,$Yt] = array_map('intval', $targetMatches);
            $Xt += $offset;
            $Yt += $offset;
            $a = (($Yb * $Xt) - ($Xb * $Yt)) / (($Xa * $Yb) - ($Ya * $Xb));
            if (is_int($a)) {
                $b = ($Xt - ($Xa * $a)) / $Xb;
                $total += $a * 3 + $b;
            }
        }
        return $total;
    }
}
