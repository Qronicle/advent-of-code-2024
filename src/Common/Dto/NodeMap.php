<?php

namespace AdventOfCode\Common\Dto;

use RuntimeException;

class NodeMap
{
    /**
     * @var MapNode[]
     */
    public array $nodes = [];

    public function addNode(MapNode $node): static
    {
        if (isset($this->nodes[$node->id])) {
            throw new RuntimeException("Node with ID [{$node->id}] already set");
        }
        $this->nodes[$node->id] = $node;
        return $this;
    }

    public function hasNodeAt(int $x, int $y): bool
    {
        return isset($this->nodes["$x,$y"]);
    }

    public function hasNode(string|MapNode $node): bool
    {
        $id = $node instanceof MapNode ? $node->id : $node;
        return isset($this->nodes[$id]);
    }

    public function connectNodes(MapNode $a, MapNode $b, int $distance, int $indexBy = 0): static
    {
        if ($indexBy === 0) {
            // Direction
            if ($b->x > $a->x) {
                $dir = 1;
            } elseif ($b->x < $a->x) {
                $dir = 3;
            } elseif ($b->y > $a->y) {
                $dir = 2;
            } else {
                $dir = 0;
            }
            $a->connections[$dir] = new NodeConnection($b, $distance);
            $b->connections[($dir + 2) % 4] = new NodeConnection($a, $distance);
        } else {
            // target node id
            $a->connections[$b->id] = new NodeConnection($b, $distance);
            $b->connections[$a->id] = new NodeConnection($a, $distance);
        }
        return $this;
    }

    /**
     * @return MapNode[]
     */
    public function deleteNode(MapNode $node): array
    {
        if (!$node->deletable) {
            throw new \LogicException('Node is not deletable');
        }
        // echo "Delete node $node->id\n";
        $updatedNodes = [];
        foreach ($node->connections as $connection) {
            foreach ($connection->node->connections as $r => $revConnection) {
                if ($revConnection->node === $node) {
                    $updatedNodes[$connection->node->id] = $connection->node;
                    unset($connection->node->connections[$r]);
                    break;
                }
            }
        }
        unset($this->nodes[$node->id]);
        return $updatedNodes;
    }

    public function removeDeadEnds(): static
    {
        $checkNodes = $this->nodes;
        while ($checkNodes) {
            $updatedNodes = [];
            foreach ($checkNodes as $node) {
                if (count($node->connections) <= 1 && $node->deletable) {
                    $updatedNodes = array_merge($updatedNodes, $this->deleteNode($node));
                }
            }
            $checkNodes = $updatedNodes;
        }
        return $this;
    }
}
