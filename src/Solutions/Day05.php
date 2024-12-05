<?php

namespace AdventOfCode\Solutions;

use AdventOfCode\Common\Solution\AbstractSolution;

class Day05 extends AbstractSolution
{
    protected function solvePart1(): int
    {
        [$dependencies, $manuals] = $this->parseInput();

        $total = 0;
        foreach ($manuals as $pages) {
            $fPages = array_flip($pages);
            $valid = true;
            foreach ($pages as $page) {
                foreach ($dependencies[$page] ?? [] as $dependantPage) {
                    if (isset($fPages[$dependantPage])) {
                        $valid = false;
                        break 2;
                    }
                }
                unset($fPages[$page]);
            }
            if ($valid) {
                $total += $pages[intval(count($pages) - 1) / 2];
            }
        }
        return $total;
    }

    protected function solvePart2(): int
    {
        return ':(';
    }

    /**
     * @return array{
     *     0: array<int, array<int>>,
     *     1: array<int, array<int>>,
     * }
     */
    protected function parseInput(): array
    {
        [$dependencyStrings, $manualStrings] = explode("\n\n", $this->rawInput);

        $dependencyStrings = explode("\n", $dependencyStrings);
        $dependencies = [];
        foreach ($dependencyStrings as $dependencyString) {
            $parts = explode('|', $dependencyString);
            $dependencies[(int) $parts[1]][] = (int) $parts[0];
        }

        $manualStrings = explode("\n", $manualStrings);
        $manuals = array_map(fn(string $manualString) => array_map('intval', explode(',', $manualString)), $manualStrings);

        return [$dependencies, $manuals];
    }
}
