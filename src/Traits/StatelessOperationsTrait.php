<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Traits;

use BradynPoulsen\Sequences\Operations\Stateless\ConstrainedOnceSequence;
use BradynPoulsen\Sequences\Operations\Stateless\TransformingSequence;
use BradynPoulsen\Sequences\Sequence;
use InvalidArgumentException;

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
        return $this->map(function ($element) use ($action) {
            call_user_func($action, $element);
            return $element;
        });
    }

    /**
     * @see Sequence::require()
     */
    public function require(callable $predicate): Sequence
    {
        return $this->map(function ($element) use ($predicate) {
            if (!call_user_func($predicate, $element)) {
                throw new InvalidArgumentException("Element must match predicate");
            }
            return $element;
        });
    }

    /**
     * @see Sequence::requireNot()
     */
    public function requireNot(callable $predicate): Sequence
    {
        return $this->map(function ($element) use ($predicate) {
            if (call_user_func($predicate, $element)) {
                throw new InvalidArgumentException("Element must not match predicate");
            }
            return $element;
        });
    }
}
