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
        $input = $this->rawInput;
        // $input = '2333133121414131402';
        $map = str_split($input);
        $id = 0;
        /** @var Part[] $parts */
        $parts = [];
        $prev = null;
        for ($i = 0; $i < count($map); $i += 2) {
            $part =  new Part($id++, $map[$i], $map[$i+1] ?? 0, $prev);
            $parts[] = $part;
            if ($prev) {
                $prev->next = $part;
            }
            $prev = $part;
        }

        $compressed = $parts;
        $firstPart = $compressed[0];
        for ($p = count($parts) - 1; $p > 0; --$p) {
            $part = $parts[$p];
            $targetPart = $firstPart;
            while ($targetPart) {
                if ($targetPart === $part) {
                    break;
                }
                if ($part->length <= $targetPart->space) {
                    $part->appendTo($targetPart);
                    break;
                }
                $targetPart = $targetPart->next;
            }
        }

        $checksum = 0;
        $position = 0;
        $part = $firstPart;
        while ($part) {
            $endPos = $position + $part->length;
            for (; $position < $endPos; ++$position) {
                $checksum += $position * $part->id;
            }
            $position += $part->space;
            $part = $part->next;
        }
        return $checksum;
    }

    protected function dumpCompressed(array $compressed): void
    {
        foreach ($compressed as $thing) {
            echo str_repeat($thing[0], $thing[1]);
        }
        echo "\n";
    }
}

class Part
{
    public function __construct(
        public int $id,
        public int $length,
        public int $space,
        public ?Part $prev = null,
        public ?Part $next = null,
    ) {
    }

    public function appendTo(Part $target): void
    {
        assert($this->prev !== null && $target->next !== null);
        $this->prev->space += $this->length + $this->space;
        $this->space = $target->space - $this->length;
        $target->space = 0;
        // Fix linked list from
        $this->prev->next = $this->next;
        if ($this->next) {
            $this->next->prev = $this->prev;
        }
        // Fix linked list to
        $this->next = $target->next;
        $target->next->prev = $this;
        $target->next = $this;
        $this->prev = $target;
    }
}