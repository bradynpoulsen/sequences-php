<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Traits;

use BradynPoulsen\Sequences\Operations\Stateless\ConstrainedOnceSequence;
use BradynPoulsen\Sequences\Operations\Stateless\TransformingSequence;
use BradynPoulsen\Sequences\Sequence;

trait StatelessOperationsTrait
{
    /**
     * @see Sequence::constrainOnce()
     */
    public function constrainOnce(): Sequence
    {
        if ($this instanceof ConstrainedOnceSequence) {
            return $this;
        }
        return new ConstrainedOnceSequence($this);
    }

    /**
     * @see Sequence::map()
     */
    public function map(callable $transform): Sequence
    {
        return new TransformingSequence($this, $transform);
    }

    /**
     * @see Sequence::onEach()
     */
    public function onEach(callable $action): Sequence
    {
        assert($this instanceof Sequence);
        return $this->map(function ($element) use ($action) {
            call_user_func($action, $element);
            return $element;
        });
    }
}
