<?php

namespace AdventOfCode\Solutions;

use AdventOfCode\Common\Solution\AbstractSolution;

class Day08 extends AbstractSolution
{
    protected function solvePart1(): int
    {
        $antennas = [];
        foreach ($this->getInputLines() as $y => $row) {
            for ($x = 0; $x < strlen($row); $x++) {
                $frequency = $row[$x];
                if ($frequency !== '.') {
                    $antennas[$frequency][] = [$x, $y];
                }
            }
        }
        $height = $y + 1;
        $width = $x;
        $antinodes = [];
        foreach ($antennas as $locations) {
            for ($a = 0; $a < count($locations) - 1; $a++) {
                for ($b = $a + 1; $b < count($locations); $b++) {
                    $diff = [$locations[$a][0] - $locations[$b][0], $locations[$a][1] - $locations[$b][1]];
                    $an = [$locations[$a][0] + $diff[0], $locations[$a][1] + $diff[1]];
                    if ($an[0] >= 0 && $an[0] < $width && $an[1] >= 0 && $an[1] < $height) {
                        $antinodes[$an[0].','.$an[1]] = true;
                    }
                    $an = [$locations[$b][0] - $diff[0], $locations[$b][1] - $diff[1]];
                    if ($an[0] >= 0 && $an[0] < $width && $an[1] >= 0 && $an[1] < $height) {
                        $antinodes[$an[0].','.$an[1]] = true;
                    }
                }
            }
        }
        return count($antinodes);
    }

    protected function solvePart2(): int
    {
        return ':(';
    }
}
