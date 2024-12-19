<?php

namespace AdventOfCode\Solutions;

use AdventOfCode\Common\Solution\AbstractSolution;

class Day17 extends AbstractSolution
{
    protected function solvePart1(): string
    {
        [$computer, $program] = $this->parseInput();
        $computer->run($program);
        return implode(',', $computer->output);
    }

    protected function solvePart2(): int
    {
        [$computer, $program] = $this->parseInput();
        $expectedOutput = implode(',', $program);
        $a = 0;
        while (++$a) {
            $computer->a = $a;
            $computer->b = 0;
            $computer->c = 0;
            $computer->run($program);
            if (implode(',', $computer->output) === $expectedOutput) {
                return $a;
            }
        }
    }

    /**
     * @return array{0: Computer, 1: int[]}
     */
    protected function parseInput(): array
    {
        preg_match_all('/\d+/', $this->rawInput, $matches);
        $program = array_map('intval', $matches[0]);
        $a = array_shift($program);
        $b = array_shift($program);
        $c = array_shift($program);
        $computer = new Computer($a, $b, $c);
        return [$computer, $program];
    }
}

class Computer
{
    public array $output;

    public function __construct(
        public int $a,
        public int $b,
        public int $c,
    ) {
    }

    /**
     * @param int[] $program
     */
    public function run(array $program): void
    {
        $this->output = [];

        for ($ptr = 0; $ptr < count($program); $ptr += 2) {
            $opcode = $program[$ptr];
            $operand = $program[$ptr + 1];
            
            switch ($opcode) {
                
                case 0:
                    $this->a = (int) ($this->a / pow(2, $this->combo($operand)));
                    break;

                case 1:
                    $this->b = $this->b ^ $operand;
                    break;

                case 2:
                    $this->b = $this->combo($operand) % 8;
                    break;

                case 3:
                    if ($this->a !== 0) {
                        $ptr = $operand - 2;
                    }
                    break;

                case 4:
                    $this->b = $this->b ^ $this->c;
                    break;

                case 5:
                    $this->output[] = $this->combo($operand) % 8;
                    break;

                case 6:
                    $this->b = (int) ($this->a / pow(2, $this->combo($operand)));
                    break;

                case 7:
                    $this->c = (int) ($this->a / pow(2, $this->combo($operand)));
                    break;
                
            }
        }
    }

    protected function combo(int $operand): int
    {
        return match($operand) {
            4 => $this->a,
            5 => $this->b,
            6 => $this->c,
            7 => throw new \RuntimeException('Unexpected operand'),
            default => $operand,
        };
    }
}