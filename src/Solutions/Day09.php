<?php

namespace AdventOfCode\Solutions;

use AdventOfCode\Common\Solution\AbstractSolution;

class Day09 extends AbstractSolution
{
    protected function solvePart1(): int
    {
        $input = $this->rawInput; // '2333133121414131402';
        $map = str_split($input);
        $firstId = 0;
        $lastId = (count($map) - 1) / 2;
        $last = null;
        $position = 0;
        $checksum = 0;
        while ($map) {
            $this->updateChecksum($checksum, $position, $firstId++, array_shift($map));
            $space = array_shift($map);
            while ($space > 0) {
                $last ??= [$lastId--, array_pop($map)];
                if ($last[1] === null) {
                    $last = null;
                    break;
                }
                if ($last[1] <= $space) {
                    $this->updateChecksum($checksum, $position, $last[0], $last[1]);
                    $space -= $last[1];
                    array_pop($map);
                    $last = null;
                } else {
                    $this->updateChecksum($checksum, $position, $last[0], $space);
                    $last[1] -= $space;
                    $space = 0;
                }
            }
            // $this->dumpCompressed($compressed);
        }
        if ($last) {
            $this->updateChecksum($checksum, $position, $last[0], $last[1]);
        }
        return $checksum;
    }

    protected function updateChecksum(int &$checksum, int &$position, int $id, int $length): void
    {
        $endPos = $position + $length;
        for (; $position < $endPos; ++$position) {
            $checksum += $position * $id;
        }
    }

    protected function solvePart2(): int
    {
        return ':(';
    }

    protected function dumpCompressed(array $compressed): void
    {
        foreach ($compressed as $thing) {
            echo str_repeat($thing[0], $thing[1]);
        }
        echo "\n";
    }
}
