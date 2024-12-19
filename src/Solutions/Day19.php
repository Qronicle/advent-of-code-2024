<?php

namespace AdventOfCode\Solutions;

use AdventOfCode\Common\Solution\AbstractSolution;

class Day19 extends AbstractSolution
{
    protected function solvePart1(): int
    {
        [$towelInput, $patternInput] = explode("\n\n", $this->rawInput);
        $towels = explode(', ', $towelInput);
        $patterns = explode("\n", $patternInput);
        $numPossible = 0;
        foreach ($patterns as $pattern) {
            $explored = [];
            $nodes = [''];
            while ($nodes) {
                $newNodes = [];
                foreach ($nodes as $node) {
                    foreach ($towels as $towel) {
                        $newNode = $node . $towel;
                        if ($newNode === $pattern) {
                            $numPossible++;
                            continue 4;
                        }
                        if (str_starts_with($pattern, $newNode) && !isset($explored[$newNode])) {
                            $explored[$newNode] = true;
                            $newNodes[] = $newNode;
                        }
                    }
                }
                $nodes = $newNodes;
            }
        }
        return $numPossible;
    }

    protected function solvePart2(): int
    {
        [$towelInput, $patternInput] = explode("\n\n", $this->rawInput);
        $towels = explode(', ', $towelInput);
        $patterns = explode("\n", $patternInput);
        $numPossible = 0;
        $cache = ['' => 1];
        foreach ($patterns as $pattern) {
            $numPossible += $this->getNumOptions($pattern, $towels, $cache);
        }
        return $numPossible;
    }

    protected function getNumOptions(string $pattern, array $towels, array &$cache): int
    {
        if (isset($cache[$pattern])) {
            return $cache[$pattern];
        }
        $total = 0;
        foreach ($towels as $towel) {
            if (str_starts_with($pattern, $towel)) {
                $total += $this->getNumOptions(substr($pattern, strlen($towel)), $towels, $cache);
            }
        }
        $cache[$pattern] = $total;
        return $total;
    }
}
