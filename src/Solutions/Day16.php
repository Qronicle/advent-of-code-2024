<?php

namespace AdventOfCode\Solutions;

use AdventOfCode\Common\Dto\MapNode;
use AdventOfCode\Common\Dto\NodeMap;
use AdventOfCode\Common\Solution\AbstractSolution;
use AdventOfCode\Common\Utils\MapUtils;

class Day16 extends AbstractSolution
{
    protected function solvePart1(): int
    {
        [$nodeMap, $startNode, $endNode] = $this->parseInput();
        $bestNodes = [];
        $routes = [[$startNode, 1, 0]];
        $bestScore = PHP_INT_MAX;
        while ($routes) {
            $newRoutes = [];
            /** @var MapNode $node */
            foreach ($routes as [$node, $dir, $score]) {
                foreach ($node->connections as $connDir => $connection) {
                    $turnCost = abs($dir - $connDir);
                    $turnCost = ($turnCost === 3 ? 1 : $turnCost) * 1000;
                    if (!isset($bestNodes[$node->id][$connDir]) || $bestNodes[$node->id][$connDir] > $score + $turnCost) {
                        $bestNodes[$node->id][$connDir] = $score + $turnCost;
                        $newScore = $score + $turnCost + $connection->distance;
                        if ($connection->node === $endNode) {
                            if ($newScore < $bestScore) {
                                $bestScore = $newScore;
                            }
                        } elseif ($newScore < $bestScore) {
                            $newRoutes[] = [$connection->node, $connDir, $score + $turnCost + $connection->distance];
                        }
                    }
                }
            }
            $routes = $newRoutes;
        }
        return $bestScore;
    }

    protected function solvePart2(): int
    {
        $bestScore = 73404;
        // $bestScore = $this->solvePart1();
        [$nodeMap, $startNode, $endNode] = $this->parseInput();
        $bestNodes = [];
        $routes = [[$startNode, 1, 0, [$startNode]]];
        $bestRoutes = [];
        while ($routes) {
            $newRoutes = [];
            /** @var MapNode $node */
            foreach ($routes as [$node, $dir, $score, $nodes]) {
                foreach ($node->connections as $connDir => $connection) {
                    $turnCost = abs($dir - $connDir);
                    $turnCost = ($turnCost === 3 ? 1 : $turnCost) * 1000;
                    if (!isset($bestNodes[$node->id][$connDir]) || $bestNodes[$node->id][$connDir] >= $score + $turnCost) {
                        $bestNodes[$node->id][$connDir] = $score + $turnCost;
                        $newScore = $score + $turnCost + $connection->distance;
                        $newNodes = [...$nodes, $connection->node];
                        if ($connection->node === $endNode) {
                            if ($newScore === $bestScore) {
                                $bestRoutes[] = $newNodes;
                            }
                        } elseif ($newScore < $bestScore) {
                            $newRoutes[] = [$connection->node, $connDir, $score + $turnCost + $connection->distance, $newNodes];
                        }
                    }
                }
            }
            $routes = $newRoutes;
        }
        $nodes = [];
        $connections = [];
        foreach ($bestRoutes as $route) {
            $prevNode = null;
            foreach ($route as $node) {
                $nodes[$node->id] = 1;
                if ($prevNode) {
                    $connectionKey = "$prevNode->id|$node->id";
                    if (!isset($connections[$connectionKey])) {
                        $dist = abs($prevNode->x - $node->x) + abs($prevNode->y - $node->y);
                        $connections[$connectionKey] = $dist - 1;
                    }
                }
                $prevNode = $node;
            }
        }
        return count($nodes) + array_sum($connections);
    }

    /**
     * @return array{0: NodeMap, 1: MapNode, 2: MapNode}
     */
    protected function parseInput(): array
    {
        $map = $this->getInputMap();
        $endPos = [count($map[0]) - 2, 1];
        $startPos = [1, count($map) - 2];
        $map[$startPos[1]][$startPos[0]] = '.';
        $map[$endPos[1]][$endPos[0]] = '.';
        $nodeMap = MapUtils::toNodeList($map, [$startPos, $endPos]);
        $nodeMap->removeDeadEnds();
        $startNode = $nodeMap->nodes[implode(',', $startPos)];
        $endNode = $nodeMap->nodes[implode(',', $endPos)];
        return [$nodeMap, $startNode, $endNode];
    }
}
