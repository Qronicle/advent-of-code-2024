<?php

namespace AdventOfCode\Solutions;

use AdventOfCode\Common\Solution\AbstractSolution;

$W = $G1 = $G2 = [];

class Day24 extends AbstractSolution
{
    protected function solvePart1(): int
    {
        [$inputs, $operations] = $this->parseInput();
        return $this->add($inputs, $operations);
    }

    /**
     * Shamelessly stolen from https://www.reddit.com/r/adventofcode/comments/1hl698z/comment/m3klg5l/?utm_source=share&utm_medium=web3x&utm_name=web3xcss&utm_term=1&utm_content=share_button
     */
    protected function solvePart2(): string
    {
        global $W, $G1, $G2;
        $W = $G1 = $G2 = [];
        $F = explode("\n\n", $this->rawInput);

        foreach (explode("\n", trim($F[0])) as $line)
            $W[substr($line, 0, 3)] = (int) substr($line, -1);

        foreach (explode("\n", trim($F[1])) as $line) {
            $line = explode(" ", $line);
            $W[$line[0]] ??= null;
            $W[$line[2]] ??= null;
            $W[$line[4]] ??= null;
            $G1[$line[4]] = [$line[0], $line[1], $line[2]];
            $G2["$line[0],$line[1],$line[2]"] = $line[4];
        }

        function f($a, $operator, $b)
        {
            global $G2;
            if (isset($G2[$key = "$a,$operator,$b"])) return $G2[$key];
            if (isset($G2[$key = "$b,$operator,$a"])) return $G2[$key];
            return false;
        }

        for ($wires = [], $i = 0; $i < 45; $i++) {
            if ($i == 0) {
                $_c = f("x00", "AND", "y00");
                assert($_c);
                continue;
            }

            $key = substr("0{$i}", -2);

            // Xi AND Yi => Ni
            $n = f("x$key", "AND", "y$key");
            // Xi XOR Yi => Mi
            $m = f("x$key", "XOR", "y$key");
            // Ci-1 AND Mi => Ri
            $r = f($_c, "AND", $m);

            if (!$r) {
                [$n, $m] = [$m, $n];
                array_push($wires, $m, $n);
                $r = f($_c, "AND", $m);
            }

            // Ci-1 XOR Mi => Zi
            $z = f($_c, "XOR", $m);

            if ($m[0] == 'z') {
                [$m, $z] = [$z, $m];
                array_push($wires, $m, $z);
            }

            if ($n[0] == 'z') {
                [$n, $z] = [$z, $n];
                array_push($wires, $n, $z);
            }

            if ($r[0] == 'z') {
                [$r, $z] = [$z, $r];
                array_push($wires, $r, $z);
            }

            // Ri OR Ni => Ci
            $c = f($r, "OR", $n);

            if ($c && $z && $c[0] == 'z' && $c !== "z45") {
                [$c, $z] = [$z, $c];
                array_push($wires, $c, $z);
            }

            // Ci-1
            $_c = $c;
        }
        sort($wires);
        return implode(",", $wires);
    }

    protected function add(array &$inputs, array $operations): int
    {
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

    protected function getValue(array $operation, array &$inputs, array $operations): int
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

    protected function parseInput(): array
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
        return [$inputs, $operations];
    }
}
