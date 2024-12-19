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
        foreach ($patterns as $pattern) {
            echo "$pattern\n";
            $nodes = [''];
            while ($nodes) {
                $newNodes = [];
                foreach ($nodes as $node) {
                    foreach ($towels as $towel) {
                        $newNode = $node . $towel;
                        if ($newNode === $pattern) {
                            $numPossible++;
                            continue;
                        }
                        if (str_starts_with($pattern, $newNode)) {
                            $newNodes[] = $newNode;
                        }
                    }
                }
                $nodes = $newNodes;
            }
        }
        return $numPossible;
    }
}
