<?php

namespace AdventOfCode\Solutions;

use AdventOfCode\Common\Solution\AbstractSolution;

class Day25 extends AbstractSolution
{
    protected function solvePart1(): int
    {
        $locks = $keys = [];
        $width = 5;
        $height = 7;
        foreach (explode("\n\n", $this->rawInput) as $shapeInput) {
            $shape = explode("\n", $shapeInput);
            if ($shape[0] === '.....') {
                $key = [];
                for ($i = 0; $i < $width; $i++) {
                    for ($h = 0; $h < $height; $h++) {
                        if ($shape[$h][$i] === '#') {
                            $key[$i] = $height - $h;
                            break;
                        }
                    }
                }
                $keys[] = $key;
            } else {
                $lock = [];
                for ($i = 0; $i < $width; $i++) {
                    for ($h = 0; $h < $height; $h++) {
                        if ($shape[$h][$i] === '.') {
                            $lock[$i] = $h;
                            break;
                        }
                    }
                }
                $locks[] = $lock;
            }
        }
        $total = 0;
        foreach ($locks as $lock) {
            foreach ($keys as $key) {
                $fit = true;
                foreach ($lock as $i => $l) {
                    if ($key[$i] + $l > $height) {
                        $fit = false;
                        break;
                    }
                }
                $total += (int) $fit;
            }
        }
        return $total;
    }

    protected function solvePart2(): string
    {
        return ':)';
    }
}
