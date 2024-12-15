<?php

namespace AdventOfCode\Common\Animate;

use AdventOfCode\Common\Animate\DisplayObject\DisplayObject;
use AdventOfCode\Common\Output\Image\Color;
use AdventOfCode\Common\Output\Image\Image;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class Stage
{
    /** @var DisplayObject[] */
    protected array $objects;

    protected int $step = 0;

    /**
     * @var array<string|int, array<string, Transition>>
     */
    protected array $transitions = [];

    protected PropertyAccessorInterface $accessor;

    public function __construct(
        public int $width,
        public int $height,
        public Color $backgroundColor,
        public string $outputPathFormat,
        public int $scale = 1,
        public int $fps = 3,
    ) {
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

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

    public function renderToPath(string $path): static
    {
        $image = new Image($this->width * $this->scale, $this->height * $this->scale, $this->backgroundColor);
        foreach ($this->children() as $object) {
            $object->render($image, $this->scale);
        }
        $image->png($path);
        return $this;
    }

    public function renderStep(?int $fps = null): static
    {
        $fps ??= $this->fps;
        dump("RENDER STEP $this->step");
        for ($f = 1; $f <= $fps; ++$f) {
            $image = new Image($this->width * $this->scale, $this->height * $this->scale, $this->backgroundColor);
            foreach ($this->children() as $object) {
                foreach ($this->transitions[$object->id()] ?? [] as $property => $transition) {
                    if ($transition->startTime + $transition->duration <= $this->step) {
                        unset($this->transitions[$object->id()][$property]);
                        continue;
                    }
                    $progress = ($this->step + $f / $fps) - $transition->startTime;
                    $this->accessor->setValue($object, $property, $transition->easing->ease($transition->startValue, $transition->endValue, $progress));
                }
                $object->render($image, $this->scale);
            }
            $path = sprintf($this->outputPathFormat, $this->step, $f);
            $this->renderToPath($path);
        }
        $this->step++;
        return $this;
    }

    /**
     * @param array<string, mixed> $targetValues [property => targetValue]
     */
    public function ease(DisplayObject $object, array $targetValues, int $duration, ?Easing $easing = null): static
    {
        foreach ($targetValues as $property => $target) {
            $currentTransition = $this->transitions[$object->id()][$property] ?? null;
            if ($currentTransition) {
                $currentTransition->startValue = $this->accessor->getValue($object, $property);
                $currentTransition->endValue = $target;
                $currentTransition->startTime = $this->step;
                $currentTransition->duration = $duration;
            } else {
                $transition = new Transition($this->accessor->getValue($object, $property), $target, $this->step, $duration, $easing ?? Easing::IN_OUT_SINE);
                $this->transitions[$object->id()][$property] = $transition;
            }
        }
        return $this;
    }
}
