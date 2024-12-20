<?php

namespace AdventOfCode\Solutions;

use AdventOfCode\Common\Animate\DisplayObject\Group;
use AdventOfCode\Common\Animate\DisplayObject\Rectangle;
use AdventOfCode\Common\Animate\Stage;
use AdventOfCode\Common\Animate\Utils\Easing;
use AdventOfCode\Common\Output\Image\Color;
use AdventOfCode\Common\Output\Image\Stroke;
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
                    while (true) {
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

    public function animate(string $input): void
    {
        $directions = [
            '^' => [0, -1],
            '>' => [1, 0],
            'v' => [0, 1],
            '<' => [-1, 0],
        ];
        [$mapInput, $directionInput] = explode("\n\n", $input);

        // Create map
        $map = [];
        $boxes = [];
        $robot = new StagedRobot(0, 0);
        $walls = [];
        foreach (explode("\n", $mapInput) as $y => $rowInput) {
            foreach (str_split($rowInput) as $tile) {
                switch ($tile) {
                    case self::BOX:
                        $box = new StagedBox(count($boxes), count($map[$y] ?? []), $y);
                        $map[$y][] = $box->id;
                        $map[$y][] = $box->id;
                        $boxes[] = $box;
                        break;
                    case self::ROBOT:
                        $robot->x = count($map[$y] ?? []);
                        $robot->y = $y;
                        $tile = self::OPEN;
                    // no break
                    default:
                        $map[$y][] = $tile;
                        $map[$y][] = $tile;
                        if ($tile === self::WALL) {
                            $walls[] = new Rectangle(count($map[$y]) - 2, $y, 2, 1, Color::hex('#555555'), Stroke::none());
                        }
                        break;
                }
            }
        }

        $stage = new Stage(
            width: count($map[0]) - 2,
            height: count($map),
            backgroundColor: Color::hex('#ccc'),
            outputPathFormat: $this->getOutputPath("%'.04d-%'.05d.png"),
            scale: 10,
            fps: 4
        );
        $group = new Group();
        $group->transform()->offset->x = -10;
        foreach ($boxes as $box) {
            $group->add($box);
        }
        foreach ($walls as $wall) {
            $group->add($wall);
        }
        $group->add($robot);
        $stage->add($group);

        $stage->renderStep(1); // Initial state frame

        $robotInstructions = str_split($directionInput);
        $step = 1;
        foreach ($robotInstructions as $instruction) {
            if ($instruction === "\n") continue;
            [$dirX, $dirY] = $directions[$instruction];
            $x = $robot->x + $dirX;
            $y = $robot->y + $dirY;
            $move = false;
            switch ($map[$y][$x]) {
                case self::WALL:
                    // do nothing
                    break;
                case self::OPEN:
                    $move = true;
                    $stage->ease($robot, ['x' => $x, 'y' => $y], 1);
                    break;
                default:
                    $box = $boxes[$map[$y][$x]];
                    $movedBoxes = [$box->id => $box];
                    $x = $box->x;
                    $y = $box->y;
                    if ($dirX !== 0) {
                        // move left or right
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
                            $stage->ease($robot, ['x' => $robot->x + $dirX], 1);
                            foreach ($movedBoxes as $box) {
                                $newX = $box->x + $dirX;
                                $stage->ease($box, ['x' => $newX], 1);
                                $map[$y][$newX] = $box->id;
                                $map[$y][$newX + 1] = $box->id;
                            }
                            $map[$y][$robot->x] = self::OPEN;
                        }
                    } else {
                        // move up or down
                        $box = $boxes[$map[$y][$x]];
                        $movedBoxes = [$box->id => $boxes[$map[$y][$x]]];
                        $prevMovedBoxes = $movedBoxes;
                        while (true) {
                            $newMovedBoxes = [];
                            foreach ($prevMovedBoxes as $movedBox) {
                                $y = $movedBox->y + $dirY;
                                for ($w = 0; $w < $movedBox->width; ++$w) {
                                    $x = $movedBox->x + $w;
                                    dump("Checking $x,$y");
                                    switch ($map[$y][$x]) {
                                        case self::WALL:
                                            dump('wall');
                                            break 4;
                                        case self::OPEN:
                                            dump('open');
                                            break;
                                        default:
                                            dump('box ' . $map[$y][$x]);
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
                            if ($movedBoxes) {
                                dump('MOVING BOXES', array_keys($movedBoxes));
                            }
                            foreach (array_reverse($movedBoxes) as $box) {
                                $map[$box->y][$box->x] = self::OPEN;
                                $map[$box->y][$box->x + 1] = self::OPEN;
                                $newY = $box->y + $dirY;
                                $stage->ease($box, ['y' => $newY], 1);
                                $map[$newY][$box->x] = $box->id;
                                $map[$newY][$box->x + 1] = $box->id;
                            }
                            $stage->ease($robot, ['y' => $robot->y + $dirY], 1);
                        }
                    }
            }
            if (!$move) {
                $this->wallHump($stage, $robot, [$dirX, $dirY]);
                die;
            } else {
                $stage->renderStep();
            }
            $map[$robot->y][$robot->x] = '@';
            echo TextOutput::map2d($map) . "\n";
            $map[$robot->y][$robot->x] = '.';
        }
    }

    protected function wallHump(Stage $stage, StagedRobot $robot, array $direction): void
    {
        $frames = ceil($stage->fps / 2);
        $x = $robot->x;
        $y = $robot->y;
        $stage->ease($robot, ['x' => $robot->x + $direction[0] * 0.3, 'y' => $robot->y + $direction[1] * 0.3], 1, Easing::IN_SINE);
        $stage->renderStep($frames);
        $stage->ease($robot, ['x' => $x, 'y' => $y], 1, Easing::OUT_SINE);
        $stage->renderStep($frames);
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

class StagedBox extends Rectangle
{
    public function __construct(
        int $id,
        int $x,
        int $y,
    ) {
        parent::__construct($x, $y, 2, 1, Color::hex('#997950'), Stroke::hairline(Color::hex('#795c32')));
        $this->id = $id;
    }
}

class StagedRobot extends Rectangle
{
    public function __construct(
        int $x,
        int $y,
    ) {
        parent::__construct($x, $y, 1, 1, Color::hex('#5555ff'), Stroke::hairline(Color::hex('#000099')));
    }
}