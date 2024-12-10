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

//    protected function findTrails(array $map, array $startPoint, array &$trails): int
//    {
//        $routes = [[$startPoint]];
//        $nextHeight = 0;
//        $numRoutes = 0;
//        while (++$nextHeight < 10 && $routes) {
//            $newRoutes = [];
//            foreach ($routes as $route) {
//                foreach (self::$dirs as $dir) {
//                    $nextX = $route[0][0] + $dir[0];
//                    $nextY = $route[0][1] + $dir[1];
//                    if (($map[$nextY][$nextX] ?? -1) !== $nextHeight) {
//                        continue;
//                    }
//                    if (isset($trails[$nextY][$nextX])) {
//                        $numRoutes += $trails[$nextY][$nextX];
//                        continue;
//                    }
//                    $newRoutes[] = [[$nextX, $nextY], ...$route];
//                }
//            }
//            $routes = $newRoutes;
//        }
//        foreach ($routes as $route) {
//            $route = array_reverse($route);
//            foreach ($route as $i => $point) {
//                echo ($i ? ' - ' : '') . $point[0] . ',' . $point[1];
//            }
//            echo "\n";
//        }
//        die;
//    }

    protected function solvePart2(): int
    {
        return ':(';
    }
}
