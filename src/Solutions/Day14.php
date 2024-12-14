<?php

namespace AdventOfCode\Solutions;

use AdventOfCode\Common\Output\Image\Color;
use AdventOfCode\Common\Output\Image\Image;
use AdventOfCode\Common\Output\ImageOutput;
use AdventOfCode\Common\Solution\AbstractSolution;
use AdventOfCode\Common\Utils\MapUtils;

class Day14 extends AbstractSolution
{
    protected function solvePart1(): int
    {
        $steps = 100;
        $width = 101;
        $height = 103;
        $halfWidth = ($width - 1) / 2;
        $halfHeight = ($height - 1) / 2;
        $quadrantTotals = [];
        foreach ($this->getInputLines() as $robotDesc) {
            preg_match_all('/-?\d+/', $robotDesc, $matches);
            [$x, $y, $velX, $velY] = array_map('intval', $matches[0]);
            $x = mod(($x + $velX * $steps), $width);
            $y = mod(($y + $velY * $steps), $height);
            if ($x !== $halfWidth && $y !== $halfHeight) {
                $quadrant = ($x < $halfWidth ? 20 : 10) + ($y < $halfHeight ? 2 : 1);
                $quadrantTotals[$quadrant] = ($quadrantTotals[$quadrant] ?? 0) + 1;
            }
        }
        return array_product($quadrantTotals);
    }

    protected function solvePart2(): int
    {
        $width = 101;
        $height = 103;
        $robots = [];
        foreach ($this->getInputLines() as $robotDesc) {
            preg_match_all('/-?\d+/', $robotDesc, $matches);
            [$x, $y, $velX, $velY] = array_map('intval', $matches[0]);
            $robots[] = new Robot($x, $y, $velX, $velY);
        }
        // return $this->animate($robots, $width, $height, 7790, 2);
        $step = 0;
        while (++$step) {
            $map = [];
            foreach ($robots as $robot) {
                $robot->x = mod($robot->x + $robot->velX, $width);
                $robot->y = mod($robot->y + $robot->velY, $height);
                $map[$robot->y][$robot->x] = 'X';
            }
            $numAbove = 0;
            foreach ($map as $y => $row) {
                if (count($row) > 32) {
                    $numAbove++;
                }
            }
            if ($numAbove > 1) {
                $map = MapUtils::createCompleteMap($map, ['w' => $width, 'h' => $height]);
                ImageOutput::map($map, $this->getOutputPath($step), 2, [' ' => [0,0,0], 'X' => [100, 255, 100]]);
                return $step;
            }
        }
    }

    /**
     * @param Robot[] $robots
     * @return void
     */
    protected function animate(array $robots, int $width, int $height, int $solutionStep, int $beforeAfterSteps): int
    {
        $fps = 99;
        $scale = 5;
        // Start some steps before solution
        foreach ($robots as $robot) {
            $robot->x = mod($robot->x + $robot->velX * ($solutionStep - $beforeAfterSteps), $width);
            $robot->y = mod($robot->y + $robot->velY * ($solutionStep - $beforeAfterSteps), $height);
        }
        $steps = $beforeAfterSteps * 2 + 1;
        for ($s = 0; $s < $steps; ++$s) {
            for ($f = 0; $f < $fps; ++$f) {
                $pct = $f / $fps;
                $image = new Image($width * $scale, $height * $scale, Color::hex('#663333'));
                foreach ($robots as $robot) {
                    $x = $robot->x + ($robot->velX * $pct);
                    if ($x < 0) {$x += $width;}
                    if ($x >= $width) {$x -= $width;}
                    $y = $robot->y + ($robot->velY * $pct);
                    if ($y < 0) {$y += $height;}
                    if ($y >= $height) {$y -= $height;}
                    $image->rect($x * $scale, $y * $scale, $scale, $scale, Color::hex('#66ff66'));
                }
                dump($s * 100 + $f);
                $image->png($this->getOutputPath($s * 100 + $f));
            }
            foreach ($robots as $robot) {
                $robot->x = mod($robot->x + $robot->velX, $width);
                $robot->y = mod($robot->y + $robot->velY, $height);
            }
            if ($s === 3) {

            }
        }
        return 0;
    }
}

class Robot
{
    public function __construct(
        public int $x,
        public int $y,
        public int $velX,
        public int $velY,
    ) {
    }
}