<?php

namespace AdventOfCode\Solutions;

use AdventOfCode\Common\Output\TextOutput;
use AdventOfCode\Common\Solution\AbstractSolution;
use AdventOfCode\Common\Utils\MapUtils;

class Day06 extends AbstractSolution
{
    public const UP = 0;
    public const RIGHT = 1;
    public const DOWN = 2;
    public const LEFT = 3;

    public static array $dir = [
        self::UP => [0, -1],
        self::RIGHT => [1, 0],
        self::DOWN => [0, 1],
        self::LEFT => [-1, 0],
    ];

    protected function solvePart1(): int
    {
        $x = 0;
        $y = 0;
        $dir = self::UP;
        $dirX = self::$dir[$dir][0];
        $dirY = self::$dir[$dir][1];
        $map = $this->getInputMap();
        foreach ($map as $y => $row) {
            foreach ($row as $x => $val) {
                if ($val === '^') {
                    $map[$y][$x] = '.';
                    break 2;
                }
            }
        }
        $count = 0;
        while (isset($map[$y][$x])) {
            switch ($map[$y][$x]) {
                case '.':
                    $map[$y][$x] = 'X';
                    ++$count;
                    // Keep walking
                case 'X':
                    $x += $dirX;
                    $y += $dirY;
                    break;
                case '#':
                    // Undo walking into #
                    $x -= $dirX;
                    $y -= $dirY;
                    // turn right and walk
                    $dir = ($dir + 1) % 4;
                    $dirX = self::$dir[$dir][0];
                    $dirY = self::$dir[$dir][1];
                    $x += $dirX;
                    $y += $dirY;
            }
        }
        return $count;
    }

    protected function solvePart2(): int
    {
        $x = 0;
        $y = 0;
        $dir = self::UP;
        $dirX = self::$dir[$dir][0];
        $dirY = self::$dir[$dir][1];
        $map = $this->getInputMap();
        foreach ($map as $y => $row) {
            foreach ($row as $x => $val) {
                if ($val === '^') {
                    $map[$y][$x] = '.';
                    break 2;
                }
            }
        }
        $startX = $x;
        $startY = $y;
        $passages = [];
        $obstructions = [];
        while (isset($map[$y][$x])) {
            switch ($map[$y][$x]) {
                case '.':
                    if (!isset($passages[$y][$x][$dir])) {
                        $passages[$y][$x][$dir] = true;
                        // Check whether we can loop!
                        $obsX = $x + $dirX;
                        $obsY = $y + $dirY;
                        if (
                            isset($map[$obsY][$obsX])
                            && !isset($passages[$obsY][$obsX])
                            && !isset($obstructions["$obsX,$obsY"])
                            && $map[$obsY][$obsX] !== '#'
                        ) {
                            $innerDir = ($dir + 1) % 4;
                            $innerDirX = self::$dir[$innerDir][0];
                            $innerDirY = self::$dir[$innerDir][1];
                            $innerX = $x + $innerDirX;
                            $innerY = $y + $innerDirY;
                            $loop = false;
                            $innerPassages = [];
                            $map[$obsY][$obsX] = '#';
                            while (isset($map[$innerY][$innerX])) {
                                if (isset($innerPassages[$innerY][$innerX][$innerDir])) {
                                    $loop = true;
                                    break;
                                }
                                $innerPassages[$innerY][$innerX][$innerDir] = true;
                                switch ($map[$innerY][$innerX]) {
                                    case '#':
                                        // Undo walking into #
                                        $innerX -= $innerDirX;
                                        $innerY -= $innerDirY;
                                        // turn right
                                        $innerDir = ($innerDir + 1) % 4;
                                        $innerDirX = self::$dir[$innerDir][0];
                                        $innerDirY = self::$dir[$innerDir][1];
                                        // And walk
                                    case '.':
                                        $innerX += $innerDirX;
                                        $innerY += $innerDirY;
                                }
                            }
                            $map[$obsY][$obsX] = '.';
                            if ($loop) {
                                $obstructions["$obsX,$obsY"] = true;
                            }
                        }
                    }
                    $x += $dirX;
                    $y += $dirY;
                    break;
                case '#':
                    // Undo walking into #
                    $x -= $dirX;
                    $y -= $dirY;
                    // turn right (don't walk!)
                    $dir = ($dir + 1) % 4;
                    $dirX = self::$dir[$dir][0];
                    $dirY = self::$dir[$dir][1];
            }
        }
        unset($obstructions["$startX,$startY"]);
        return count($obstructions);
    }
}
