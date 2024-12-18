<?php

namespace AdventOfCode\Common\Utils;

use AdventOfCode\Common\Dto\MapNode;
use AdventOfCode\Common\Dto\NodeMap;

/**
 * Class MapUtils
 *
 * @package AdventOfCode\Common\Utils
 * @author  Ruud Seberechts
 */
class MapUtils
{
    public static function createCompleteMap(
        array $partialMap,
        array $bounds,
        string $void = ' ',
        ?array $extra = null,
        bool $reverseY = false
    ): array {
        $l = $bounds['l'] ?? $bounds['x'] ?? 0;
        $r = $bounds['r'] ?? $l + $bounds['w'];
        $w = $bounds['w'] ?? $r - $l + 1;
        $t = $bounds['t'] ?? $bounds['y'] ?? 0;
        $b = $bounds['b'] ?? $t + $bounds['h'];
        $h = $bounds['h'] ?? $b - $t + 1;
        $map = array_fill($t, $h, array_fill($l, $w, $void));
        foreach ($partialMap as $y => $row) {
            foreach ($row as $x => $value) {
                $map[$y][$x] = is_string($value) ? $value : ($value ? '#' : '.');
            }
        }
        foreach ($extra ?? [] as $char => $extraInfo) {
            if (is_numeric(key($extraInfo))) {
                $extraInfo = ['coords' => $extraInfo];
            }
            $char = $extraInfo['char'] ?? $char;
            $coords = $extraInfo['coords'];
            $offset = $extraInfo['offset'] ?? [0, 0];
            foreach ($coords as $crds) {
                $map[$crds[1] + $offset[1]][$crds[0] + $offset[0]] = $char;
            }
        }
        if ($reverseY) {
            $map = array_reverse($map);
        }
        return $map;
    }

    public static function toNodeList(array $map, array $fixedPoints): NodeMap
    {
        $dirs = [[0, -1], [1, 0], [0, 1], [-1, 0]];
        $pathChar = '.';
        $nodeMap = new NodeMap();
        $startNodes = [];
        foreach ($fixedPoints as $point) {
            $node = new MapNode($point[0], $point[1], false);
            $nodeMap->addNode($node);
            $startNodes[$node->id] = $node;
        }
        while ($startNodes) {
            // echo " > Finding connections for nodes [" . implode('],[', array_keys($startNodes)) . "]\n";
            $newStartNodes = [];
            foreach ($startNodes as $startNode) {
                foreach ($dirs as $d => $dir) {
                    $x = $startNode->x;
                    $y = $startNode->y;
                    $dist = 0;
                    $endNode = null;
                    while (++$dist) {
                        $x += $dir[0];
                        $y += $dir[1];
                        if ($map[$y][$x] !== $pathChar) {
                            break;
                        }
                        foreach ($dirs as $ad => $altDir) {
                            if (($d + 2) % 4 === $ad) {
                                continue; // opposite direction
                            }
                            if ($ad === $d) {
                                if (($map[$y + $altDir[1]][$x + $altDir[0]] ?? null) !== $pathChar) {
                                    $endNode = new MapNode($x, $y);
                                    break 2;
                                }
                            } else {
                                if (($map[$y + $altDir[1]][$x + $altDir[0]] ?? null) === $pathChar) {
                                    $endNode = new MapNode($x, $y);
                                    break 2;
                                }
                            }
                        }
                    }
                    if ($endNode) {
                        // echo "   -> Found node at [$x,$y] for [$startNode->id] (distance of $dist)\n";
                        if ($nodeMap->hasNode($endNode)) {
                            $endNode = $nodeMap->nodes[$endNode->id];
                        } else {
                            $nodeMap->addNode($endNode);
                            $newStartNodes[$endNode->id] = $endNode;
                        }
                        $nodeMap->connectNodes($startNode, $endNode, $dist);
                    }
                }
            }
            $startNodes = $newStartNodes;
        }
        return $nodeMap;
    }
}
