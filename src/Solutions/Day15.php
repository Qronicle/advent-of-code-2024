<?php

namespace AdventOfCode\Solutions;

use AdventOfCode\Common\Output\TextOutput;
use AdventOfCode\Common\Solution\AbstractSolution;

class Day15 extends AbstractSolution
{
    public const ROBOT = '@';
    public const WALL = '#';
    public const OPEN = '.';
    public const BOX = 'O';

    protected function solvePart1(): int
    {
        $directions = [
            '^' => [0, -1],
            '>' => [1, 0],
            'v' => [0, 1],
            '<' => [-1, 0],
        ];
        [$mapInput, $directionInput] = explode("\n\n", $this->rawInput);
        [$map, $position] = $this->getInputMapAndPosition(self::ROBOT, $mapInput);
        $map[$position[1]][$position[0]] = self::OPEN;
        $robotInstructions = str_split($directionInput);
        foreach ($robotInstructions as $instruction) {
            if ($instruction === "\n") continue;
            [$dirX, $dirY] = $directions[$instruction];
            $x = $position[0] + $dirX;
            $y = $position[1] + $dirY;
            switch ($map[$y][$x]) {
                case self::WALL:
                    // do nothing
                    break;
                case self::OPEN:
                    $position[0] = $x;
                    $position[1] = $y;
                    break;
                case self::BOX:
                    $box = [$x, $y];
                    $move = false;
                    while(true) {
                        $x += $dirX;
                        $y += $dirY;
                        switch ($map[$y][$x]) {
                            case self::WALL:
                                break 2;
                            case self::OPEN:
                                $move = true;
                                break 2;
                        }
                    }
                    if ($move) {
                        // Only move first and last boxes
                        $map[$box[1]][$box[0]] = self::OPEN;
                        $map[$y][$x] = self::BOX;
                        $position = $box;
                    }
                    break;
            }
        }
//        $map[$position[1]][$position[0]] = '@';
//        echo TextOutput::map2d($map);

        $total = 0;
        foreach ($map as $y => $row) {
            foreach ($row as $x => $tile) {
                if ($tile === self::BOX) {
                    $total += 100 * $y + $x;
                }
            }
        }
        return $total;
    }

    protected function solvePart2(): int
    {
        $directions = [
            '^' => [0, -1],
            '>' => [1, 0],
            'v' => [0, 1],
            '<' => [-1, 0],
        ];
        [$mapInput, $directionInput] = explode("\n\n", $this->rawInput);

        // Create map
        $map = [];
        $boxes = [];
        $position = null;
        foreach (explode("\n", $mapInput) as $y => $rowInput) {
            foreach (str_split($rowInput) as $tile) {
                switch ($tile) {
                    case self::BOX:
                        $box = new Box(count($boxes), count($map[$y] ?? []), $y);
                        $map[$y][] = $box->id;
                        $map[$y][] = $box->id;
                        $boxes[] = $box;
                        break;
                    case self::ROBOT:
                        $position = [count($map[$y] ?? []), $y];
                        $tile = self::OPEN;
                        // no break
                    default:
                        $map[$y][] = $tile;
                        $map[$y][] = $tile;
                        break;
                }
            }
        }

        $robotInstructions = str_split($directionInput);
        foreach ($robotInstructions as $instruction) {
            if ($instruction === "\n") continue;
            // dump("Move $instruction");
            [$dirX, $dirY] = $directions[$instruction];
            $x = $position[0] + $dirX;
            $y = $position[1] + $dirY;
            switch ($map[$y][$x]) {
                case self::WALL:
                    // do nothing
                    break;
                case self::OPEN:
                    $position[0] = $x;
                    $position[1] = $y;
                    break;
                default:
                    $box = $boxes[$map[$y][$x]];
                    $movedBoxes = [$box->id => $box];
                    $x = $box->x;
                    $y = $box->y;
                    if ($dirX !== 0) {
                        // move left or right
                        $move = false;
                        while (true) {
                            $x += $dirX > 0 ? +2 : -1;
                            switch ($map[$y][$x]) {
                                case self::WALL:
                                    break 2;
                                case self::OPEN:
                                    $move = true;
                                    break 2;
                                default:
                                    if (!isset($boxes[$map[$y][$x]])) {
                                        dd('noo');
                                    }
                                    $box = $boxes[$map[$y][$x]];
                                    $movedBoxes[$box->id] = $boxes[$map[$y][$x]];
                            }
                        }
                        if ($move) {
                            $position[0] += $dirX;
                            foreach ($movedBoxes as $box) {
                                // dump($box);
                                $box->x += $dirX;
                                $map[$y][$box->x] = $box->id;
                                $map[$y][$box->x + 1] = $box->id;
                            }
                            $map[$y][$position[0]] = self::OPEN;
                        }
                    } else {
                        // move up or down
                        $move = false;
                        $movedBoxes = [$boxes[$map[$y][$x]]];
                        $prevMovedBoxes = $movedBoxes;
                        while (true) {
                            $newMovedBoxes = [];
                            foreach ($prevMovedBoxes as $movedBox) {
                                $y = $movedBox->y + $dirY;
                                for ($w = 0; $w < 2; ++$w) {
                                    $x = $movedBox->x + $w;
                                    switch ($map[$y][$x]) {
                                        case self::WALL:
                                            break 4;
                                        case self::OPEN:
                                            break;
                                        default:
                                            $newBox = $boxes[$map[$y][$x]];
                                            $newMovedBoxes[$newBox->id] = $newBox;
                                            $movedBoxes[$newBox->id] = $newBox;
                                    }
                                }
                            }
                            if (!$newMovedBoxes) {
                                $move = true;
                                break;
                            }
                            $prevMovedBoxes = $newMovedBoxes;
                        }
                        if ($move) {
                            foreach (array_reverse($movedBoxes) as $box) {
                                $map[$box->y][$box->x] = self::OPEN;
                                $map[$box->y][$box->x + 1] = self::OPEN;
                                $box->y += $dirY;
                                $map[$box->y][$box->x] = $box->id;
                                $map[$box->y][$box->x + 1] = $box->id;
                            }
                            $position[1] += $dirY;
                        }
                    }
            }
        }
//            $map[$position[1]][$position[0]] = '@';
//            echo TextOutput::map2d($map) . "\n";
//            $map[$position[1]][$position[0]] = '.';
        $total = 0;
        foreach ($boxes as $box) {
            $total += 100 * $box->y + $box->x;
        }
        return $total;
    }
}

class Box
{
    public function __construct(
        public int $id,
        public int $x,
        public int $y,
    ) {
    }
}