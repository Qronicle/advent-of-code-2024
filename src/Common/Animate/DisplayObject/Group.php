<?php

namespace AdventOfCode\Common\Animate\DisplayObject;

use AdventOfCode\Common\Animate\DisplayObject\Common\AbstractDisplayObject;
use AdventOfCode\Common\Animate\DisplayObject\Common\DisplayObjectGroup;

class Group extends AbstractDisplayObject
{
    use DisplayObjectGroup;

    public function __construct()
    {
        parent::__construct(null);
    }
}
