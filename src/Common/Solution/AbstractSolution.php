<?php

namespace AdventOfCode\Common\Solution;

/**
 * Class AbstractSolution
 *
 * @package AdventOfCode\Common\Solution
 * @author  Ruud Seberechts
 */
abstract class AbstractSolution
{
    protected string $rawInput;
    protected int $aocPart;

    public function solve(int $part, string $input): string
    {
        $this->aocPart = $part;
        $method = 'solvePart' . $part;
        $this->rawInput = $input;
        return $this->$method();
    }

    abstract protected function solvePart1(): int|string;

    abstract protected function solvePart2(): int|string;

    protected function getInputLines(): array
    {
        return explode("\n", $this->rawInput);
    }

    /**
     * @return array<int, array<int, string>>
     */
    protected function getInputMap(?string $input = null): array
    {
        $input ??= $this->rawInput;
        return array_map(fn (string $val) => str_split($val), explode("\n", $input));
    }

    /**
     * @return array{0: array<int, array<int, string>>, 1: ?int[]>
     */
    protected function getInputMapAndPosition(string $search, ?string $input = null): array
    {
        $input ??= $this->rawInput;
        $map = [];
        $position = null;
        foreach (explode("\n", $input) as $y => $row) {
            foreach (str_split($row) as $x => $val) {
                $map[$y][$x] = $val;
                if ($val === $search) {
                    $position = [$x, $y];
                }
            }
        }
        return [$map, $position];
    }

    /**
     * @return array<int, array<int, int>>
     */
    protected function getIntInputMap(?string $input = null): array
    {
        $input ??= $this->rawInput;
        return array_map(fn (string $val) => array_map('intval', str_split($val)), explode("\n", $input));
    }

    public function getOutputPath(string|int|null $filename = null, int $pad = 5, string $ext = 'png'): string
    {
        $path = BASE_DIR . '/var/out/day' . static::getDay();
        if ($filename === null) {
            return $path;
        }
        if (is_int($filename)) {
            $filename = str_pad($filename, $pad, '0', STR_PAD_LEFT) . ".$ext";
        }
        return "$path/$filename";
    }

    public static function getDay(): int
    {
        $end = substr(static::class, -2);
        return (int) (is_numeric($end) ? $end : substr($end, 1));
    }
}
