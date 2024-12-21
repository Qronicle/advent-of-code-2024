<?php

namespace AdventOfCode\Solutions;

use AdventOfCode\Common\Solution\AbstractSolution;

class Day21 extends AbstractSolution
{
    protected array $movementMatrix;
    protected int $maxLevel;
    protected array $cache = [];

    public static array $dirs = [
        '^' => [0, -1],
        '>' => [1, 0],
        'v' => [0, 1],
        '<' => [-1, 0],
    ];

    protected function solvePart1(): int
    {
        return $this->calculate(2);
    }

    protected function solvePart2(): int
    {
        return $this->calculate(25);
    }

    protected function calculate(int $numDirRobots): int
    {
        $this->createMovementMatrix();
        $this->maxLevel = $numDirRobots;

        $total = 0;
        foreach ($this->getInputLines() as $code) {
            $current = 'A';
            $length = 0;
            foreach (str_split($code) as $targetBtn) {
                $length += $this->handleButtonPress($current, $targetBtn, $this->maxLevel);
                $current = $targetBtn;
            }
            $total += substr($code, 0, -1) * $length;
        }
        return $total;
    }

    protected function handleButtonPress(int|string $srcBtn, int|string $targetBtn, int $level): int
    {
        $movementMatrix = $this->movementMatrix[$level === $this->maxLevel ? 'num' : 'dir'];
        if ($level === 0) {
            return count($movementMatrix[$srcBtn][$targetBtn][0]);
        }
        if (isset($this->cache[$level][$srcBtn][$targetBtn])) {
            return $this->cache[$level][$srcBtn][$targetBtn];
        }
        $min = PHP_INT_MAX;
        foreach ($movementMatrix[$srcBtn][$targetBtn] as $moves) {
            $steps = 0;
            $current = 'A';
            foreach ($moves as $move) {
                $steps += $this->handleButtonPress($current, $move, $level - 1);
                $current = $move;
            }
            $min = min($steps, $min);
        }
        $this->cache[$level][$srcBtn][$targetBtn] = $min;
        return $min;
    }

    protected function createMovementMatrix(): void
    {
        $pads = [
            'num' => [
                [7, 8, 9],
                [4, 5, 6],
                [1, 2, 3],
                [1 => 0, 'A'],
            ],
            'dir' => [
                [1 => '^', 'A'],
                ['<', 'v', '>'],
            ],
        ];
        $movementMatrix = [];
        foreach ($pads as $padType => $pad) {
            $matrix = [];
            $buttons = [];
            foreach ($pad as $y => $row) {
                foreach ($row as $x => $btn) {
                    $buttons[$btn] = [$x, $y];
                }
            }
            ksort($buttons);
            foreach ($buttons as $srcBtn => [$srcX, $srcY]) {
                foreach ($buttons as $targetBtn => [$targetX, $targetY]) {
                    if ($srcBtn === $targetBtn) {
                        $matrix[$srcBtn][$targetBtn] = [['A']];
                        continue;
                    }
                    $x = $targetX - $srcX;
                    $y = $targetY - $srcY;
                    $xMoves = array_fill(0, abs($x), $x > 0 ? '>' : '<');
                    $yMoves = array_fill(0, abs($y), $y > 0 ? 'v' : '^');
                    if ($xMoves xor $yMoves) {
                        $matrix[$srcBtn][$targetBtn] = [[...$xMoves, ...$yMoves, 'A']];
                    } else {
                        if (isset($pad[$srcY][$targetX])) {
                            $matrix[$srcBtn][$targetBtn][] = [...$xMoves, ...$yMoves, 'A'];
                        }
                        if (isset($pad[$targetY][$srcX])) {
                            $matrix[$srcBtn][$targetBtn][] = [...$yMoves, ...$xMoves, 'A'];
                        }
                    }
                }
            }
            $movementMatrix[$padType] = $matrix;
        }
        $this->movementMatrix = $movementMatrix;
    }
}
