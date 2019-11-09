<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Traits;

use BradynPoulsen\Sequences\Operations\Stateless\{
    ConstrainedOnceSequence,
    DefaultingWhenEmptySequence,
    DropSequence,
    DropWhileSequence,
    FlatteningSequence,
    MergingSequence,
    TakeSequence,
    TakeWhileSequence,
    TransformingSequence,
    ZippingSequence,
    ZippingWithNextSequence
};
use BradynPoulsen\Sequences\Sequence;
use InvalidArgumentException;
use Iterator;

use function BradynPoulsen\Sequences\emptySequence;

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
     * @see Sequence::drop()
     */
    public function drop(int $count): Sequence
    {
        if ($count === 0) {
            return $this;
        }
        return new DropSequence($this, $count);
    }

    /**
     * @see Sequence::dropWhile()
     */
    public function dropWhile(callable $predicate): Sequence
    {
        return new DropWhileSequence($this, $predicate);
    }

    /**
     * @see Sequence::flatMap()
     */
    public function flatMap(callable $transform): Sequence
    {
        return new FlatteningSequence($this, $transform);
    }

    /**
     * @see Sequence::flatten()
     */
    public function flatten(): Sequence
    {
        return new FlatteningSequence($this);
    }

    /**
     * @see Sequence::ifEmpty()
     */
    public function ifEmpty(callable $supplier): Sequence
    {
        return new DefaultingWhenEmptySequence($this, $supplier);
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
     * @see Sequence::plus()
     */
    public function plus(iterable $elements): Sequence
    {
        $sequence = new MergingSequence($this, $elements);
        if ($elements instanceof Iterator) {
            $sequence = $sequence->constrainOnce();
        }
        return $sequence;
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

    /**
     * @see Sequence::take()
     */
    public function take(int $count): Sequence
    {
        if ($count === 0) {
            return emptySequence();
        }
        return new TakeSequence($this, $count);
    }

    /**
     * @see Sequence::takeWhile()
     */
    public function takeWhile(callable $predicate): Sequence
    {
        return new TakeWhileSequence($this, $predicate);
    }

    /**
     * @see Sequence::zip()
     */
    public function zip(Sequence $other, ?callable $transform = null): Sequence
    {
        return new ZippingSequence($this, $other, $transform);
    }

    /**
     * @see Sequence::zipWithNext()
     */
    public function zipWithNext(?callable $transform = null): Sequence
    {
        return new ZippingWithNextSequence($this, $transform);
    }
}
