<?php

namespace AdventOfCode\Solutions;

use AdventOfCode\Common\Solution\AbstractSolution;

class Day10 extends AbstractSolution
{
    protected static array $dirs = [[0, 1], [1, 0], [0, -1], [-1, 0]];

    protected function solvePart1(): int
    {
        $map = $this->getIntInputMap();
        $total = 0;
        foreach ($map as $y => $row) {
            foreach ($row as $x => $height) {
                if ($height === 0) {
                    $total += $this->findTrails($map, [$x, $y]);
                }
            }
        }
        return $total;
    }

    protected function findTrails(array $map, array $startPoint): int
    {
        $points = [$startPoint];
        $nextHeight = 0;
        while (++$nextHeight < 10 && $points) {
            $newPoints = [];
            foreach ($points as $point) {
                foreach (self::$dirs as $dir) {
                    $x = $point[0] + $dir[0];
                    $y = $point[1] + $dir[1];
                    if (($map[$y][$x] ?? -1) === $nextHeight) {
                        $newPoints["$x,$y"] = [$x,$y];
                    }
                }
            }
            $points = $newPoints;
        }
        return count($points);
    }

    protected function solvePart2(): int
    {
        $map = $this->getIntInputMap();
        $total = 0;
        foreach ($map as $y => $row) {
            foreach ($row as $x => $height) {
                if ($height === 0) {
                    $total += $this->findTrailsXxl($map, [$x, $y]);
                }
            }
        }
        return $total;
    }

    protected function findTrailsXxl(array $map, array $startPoint): int
    {
        $routes = [[...$startPoint, 1]];
        $nextHeight = 0;
        while (++$nextHeight < 10 && $routes) {
            $newRoutes = [];
            foreach ($routes as $route) {
                foreach (self::$dirs as $dir) {
                    $x = $route[0] + $dir[0];
                    $y = $route[1] + $dir[1];
                    if (($map[$y][$x] ?? -1) === $nextHeight) {
                        if (isset($newRoutes["$x,$y"])) {
                            $newRoutes["$x,$y"][2] += $route[2];
                        } else {
                            $newRoutes["$x,$y"] = [$x, $y, $route[2]];
                        }
                    }
                }
            }
            $routes = $newRoutes;
        }
        $total = 0;
        foreach ($routes as $route) {
            $total += $route[2];
        }
        return $total;
    }
}
