<?php

namespace AdventOfCode\Common\Animate\DisplayObject\Common;

use AdventOfCode\Common\Animate\Utils\Transform;
use AdventOfCode\Common\Output\Image\Image;

trait DisplayObjectGroup
{
    /** @var DisplayObject[] */
    protected array $objects = [];

    public function add(DisplayObject $object, int|string|null $id = null): static
    {
        $id = $object->hasId() ? $object->id() : $id;
        if ($id !== null) {
            $this->objects[$id] = $object;
        } else {
            $this->objects[] = $object;
            $id = array_key_last($this->objects);
            $object->setId($id);
        }
        return $this;
    }

    public function get(int|string $id): DisplayObject
    {
        return $this->objects[$id] ?? throw new \LogicException("Object with ID '$id' does not exist");
    }

    /**
     * @return DisplayObject[]
     */
    public function children(): array
    {
        return $this->objects;
    }

    public function render(Image $image, Transform $transform): void
    {
        $t = $transform->add($this->transform());
        foreach ($this->children() as $object) {
            $object->render($image, $t);
        }
    }
}
