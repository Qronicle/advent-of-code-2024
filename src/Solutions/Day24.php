<?php

namespace AdventOfCode\Solutions;

use AdventOfCode\Common\Solution\AbstractSolution;

class Day24 extends AbstractSolution
{
    protected function solvePart1(): int
    {
        [$startValuesInput, $operationsInput] = explode("\n\n", $this->rawInput);
        $inputs = [];
        foreach (explode("\n", $startValuesInput) as $line) {
            [$key, $value] = explode(': ', $line);
            $inputs[$key] = (int) $value;
        }
        $operations = [];
        foreach (explode("\n", $operationsInput) as $line) {
            preg_match('/([a-z0-9]{3}) ([A-Z]+) ([a-z0-9]{3}) -> ([a-z0-9]{3})/', $line, $operation);
            array_shift($operation);
            $operations[array_pop($operation)] = $operation;
        }
        foreach ($operations as $targetInput => $operation) {
            if (isset($inputs[$targetInput])) {
                continue;
            }
            $inputs[$targetInput] = $this->getValue($operation, $inputs, $operations);
        }
        $z = [];
        foreach ($inputs as $key => $value) {
            if ($key[0] === 'z') {
                $z[(int) substr($key, 1)] = (int) $value;
            }
        }
        ksort($z);
        $z = array_reverse($z);
        return intval(implode('', $z), 2);
    }

    protected function getValue(array $operation, array $inputs, array $operations): int
    {
        [$a, $gate, $b] = $operation;
        foreach ([$a, $b] as $input) {
            if (!isset($inputs[$input])) {
                $inputs[$input] = $this->getValue($operations[$input], $inputs, $operations);
            }
        }
        return (int) match ($gate) {
            'AND' => $inputs[$a] & $inputs[$b],
            'OR' => $inputs[$a] | $inputs[$b],
            'XOR' => $inputs[$a] ^ $inputs[$b],
        };
    }

    protected function solvePart2(): int
    {
        return ':(';
    }
}
