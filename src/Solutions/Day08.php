<?php

namespace AdventOfCode\Solutions;

use AdventOfCode\Common\Solution\AbstractSolution;

class Day08 extends AbstractSolution
{
    protected function solvePart1(): int
    {
        [$antennas, $width, $height] = $this->parseInput();
        $antinodes = [];
        foreach ($antennas as $locations) {
            for ($a = 0; $a < count($locations) - 1; $a++) {
                for ($b = $a + 1; $b < count($locations); $b++) {
                    $diff = [$locations[$a][0] - $locations[$b][0], $locations[$a][1] - $locations[$b][1]];
                    $an = [$locations[$a][0] + $diff[0], $locations[$a][1] + $diff[1]];
                    if ($an[0] >= 0 && $an[0] < $width && $an[1] >= 0 && $an[1] < $height) {
                        $antinodes[$an[0] . ',' . $an[1]] = true;
                    }
                    $an = [$locations[$b][0] - $diff[0], $locations[$b][1] - $diff[1]];
                    if ($an[0] >= 0 && $an[0] < $width && $an[1] >= 0 && $an[1] < $height) {
                        $antinodes[$an[0] . ',' . $an[1]] = true;
                    }
                }
            }
        }
        return count($antinodes);
    }

    protected function solvePart2(): int
    {
        [$antennas, $width, $height] = $this->parseInput();
        $antinodes = [];
        foreach ($antennas as $locations) {
            for ($a = 0; $a < count($locations) - 1; $a++) {
                for ($b = $a + 1; $b < count($locations); $b++) {
                    $diff = [$locations[$a][0] - $locations[$b][0], $locations[$a][1] - $locations[$b][1]];
                    $x = $locations[$a][0];
                    $y = $locations[$a][1];
                    $antinodes["$x,$y"] = true;
                    while (true) {
                        $x -= $diff[0];
                        $y -= $diff[1];
                        if ($x >= 0 && $x < $width && $y >= 0 && $y < $height) {
                            $antinodes["$x,$y"] = true;
                        } else {
                            break;
                        }
                    }
                    while (true) {
                        $x += $diff[0];
                        $y += $diff[1];
                        if ($x >= 0 && $x < $width && $y >= 0 && $y < $height) {
                            $antinodes["$x,$y"] = true;
                        } else {
                            break;
                        }
                    }
                }
            }
        }
        return count($antinodes);
    }

    protected function parseInput(): array
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
        return [$antennas, $x, $y + 1];
    }
}
