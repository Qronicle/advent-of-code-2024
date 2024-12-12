<?php

namespace AdventOfCode\Solutions;

use AdventOfCode\Common\Solution\AbstractSolution;

class Day12 extends AbstractSolution
{
    protected function solvePart1(): int
    {
        $map = $this->getInputMap();
        $plots = ['0,0' => [0, 0]];
        $dirs = [[0, 1], [1, 0], [-1, 0], [0, -1]];
        $totalPrice = 0;
        while ($plots) {
            $newPlots = [];
            foreach ($plots as $startPoint) {
                $plotName = $map[$startPoint[1]][$startPoint[0]];
                if ($plotName === ' ') {
                    continue;
                }
                $plotPoints = [];
                $plotArea = 0;
                $plotPerimeter = 0;
                $points = [$startPoint];
                while ($points) {
                    $newPoints = [];
                    foreach ($points as [$x, $y]) {
                        if ($map[$y][$x] === $plotName) {
                            $plotArea++;
                            $map[$y][$x] = ' ';
                            $plotPoints[$y][$x] = true;
                            foreach ($dirs as [$dirX, $dirY]) {
                                $adjX = $x + $dirX;
                                $adjY = $y + $dirY;
                                if ($adjPlotName = $map[$adjY][$adjX] ?? null) {
                                    if ($adjPlotName === $plotName) {
                                        $newPoints["$adjX,$adjY"] = [$adjX,$adjY];
                                    } elseif (!isset($plotPoints[$adjY][$adjX])) {
                                        $newPlots["$adjX,$adjY"] = [$adjX,$adjY];
                                        $plotPerimeter++;
                                    }
                                } else {
                                    $plotPerimeter++;
                                }
                            }
                        }
                    }
                    $points = $newPoints;
                }
                $totalPrice += $plotArea * $plotPerimeter;
            }
            $plots = $newPlots;
        }
        return $totalPrice;
    }

    protected function solvePart2(): int
    {
        $map = $this->getInputMap();
        $plots = ['0,0' => [0, 0]];
        $dirs = [[0, -1], [1, 0], [0, 1], [-1, 0]]; // up and down % 2 = 0
        $totalPrice = 0;
        while ($plots) {
            $newPlots = [];
            foreach ($plots as $startPoint) {
                $plotName = $map[$startPoint[1]][$startPoint[0]];
                if ($plotName === ' ') {
                    continue;
                }
                $plotPoints = [];
                $plotArea = 0;
                $points = [$startPoint];
                $perimeterPoints = [];
                while ($points) {
                    $newPoints = [];
                    foreach ($points as [$x, $y]) {
                        if ($map[$y][$x] === $plotName) {
                            $plotArea++;
                            $map[$y][$x] = ' ';
                            $plotPoints[$y][$x] = true;
                            foreach ($dirs as $dir => [$dirX, $dirY]) {
                                $adjX = $x + $dirX;
                                $adjY = $y + $dirY;
                                if ($adjPlotName = $map[$adjY][$adjX] ?? null) {
                                    if ($adjPlotName === $plotName) {
                                        $newPoints["$adjX,$adjY"] = [$adjX,$adjY];
                                    } elseif (!isset($plotPoints[$adjY][$adjX])) {
                                        $newPlots["$adjX,$adjY"] = [$adjX,$adjY];
                                        $vertical = $dir % 2 === 0;
                                        $perimeterPoints[$dir][$vertical ? $x : $y][$vertical ? $y : $x] = true;
                                    }
                                } else {
                                    $vertical = $dir % 2 === 0;
                                    $perimeterPoints[$dir][$vertical ? $x : $y][$vertical ? $y : $x] = true;
                                }
                            }
                        }
                    }
                    $points = $newPoints;
                }
                $plotPerimeter = 0;
                foreach ($perimeterPoints as $p => $positions) {
                    ksort($positions);
                    $prevPositions = [];
                    foreach ($positions as $ps) {
                        foreach ($ps as $p => $tmp) {
                            $plotPerimeter += isset($prevPositions[$p]) ? 0 : 1;
                        }
                        $prevPositions = $ps;
                    }
                }
                $totalPrice += $plotArea * $plotPerimeter;
            }
            $plots = $newPlots;
        }
        return $totalPrice;
    }
}
