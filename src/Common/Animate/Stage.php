<?php

namespace AdventOfCode\Common\Animate;

use AdventOfCode\Common\Animate\DisplayObject\Common\DisplayObject;
use AdventOfCode\Common\Animate\DisplayObject\Common\DisplayObjectGroup;
use AdventOfCode\Common\Animate\Utils\Easing;
use AdventOfCode\Common\Animate\Utils\Transform;
use AdventOfCode\Common\Animate\Utils\Transition;
use AdventOfCode\Common\Output\Image\Color;
use AdventOfCode\Common\Output\Image\Image;
use SplObjectStorage;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class Stage
{
    use DisplayObjectGroup {
        render as renderGroup;
    }

    protected int $step = 0;

    protected Transform $transform;

    /**
     * @var SplObjectStorage<DisplayObject, array<string, Transition>>
     */
    protected SplObjectStorage $transitions;

    protected PropertyAccessorInterface $accessor;

    public function __construct(
        public int $width,
        public int $height,
        public Color $backgroundColor,
        public ?string $outputPathFormat = null,
        public int $scale = 1,
        public int $fps = 3,
    ) {
        $this->transitions = new SplObjectStorage();
        $this->accessor = PropertyAccess::createPropertyAccessor();
        $this->transform = new Transform(scale: $this->scale);
    }

    public function renderToPath(string $path): static
    {
        $image = new Image($this->width * $this->scale, $this->height * $this->scale, $this->backgroundColor);
        foreach ($this->children() as $object) {
            $object->render($image, $this->transform);
        }
        $image->png($path);
        return $this;
    }

    public function renderStep(?int $fps = null): static
    {
        if ($this->outputPathFormat === null) {
            throw new \LogicException('Please set stage.outputPathFormat before rendering animations');
        }
        $fps ??= $this->fps;
        dump("RENDER STEP $this->step");
        for ($f = 1; $f <= $fps; ++$f) {
            // $image = new Image($this->width * $this->scale, $this->height * $this->scale, $this->backgroundColor);
            foreach ($this->transitions as $object) {
                $objectTransitions = $this->transitions[$object];
                foreach ($objectTransitions as $property => $transition) {
                    if ($transition->startTime + $transition->duration <= $this->step) {
                        unset($objectTransitions[$property]);
                        continue;
                    }
                    $progress = ($this->step + $f / $fps) - $transition->startTime;
                    $this->accessor->setValue($object, $property, $transition->easing->ease($transition->startValue, $transition->endValue, $progress));
                }
                if ($objectTransitions) {
                    $this->transitions[$object] = $objectTransitions;
                } else {
                    unset($this->transitions[$object]);
                }
            }
            // $object->render($image, $this->scale);
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
        $objectTransitions = $this->transitions[$object] ?? [];
        foreach ($targetValues as $property => $target) {
            $currentTransition = $objectTransitions[$property] ?? null;
            if ($currentTransition) {
                $currentTransition->startValue = $this->accessor->getValue($object, $property);
                $currentTransition->endValue = $target;
                $currentTransition->startTime = $this->step;
                $currentTransition->duration = $duration;
            } else {
                $transition = new Transition($this->accessor->getValue($object, $property), $target, $this->step, $duration, $easing ?? Easing::IN_OUT_SINE);
                $objectTransitions[$property] = $transition;
            }
        }
        $this->transitions[$object] = $objectTransitions;
        return $this;
    }
}
