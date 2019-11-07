<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Traits;

use BradynPoulsen\Sequences\Builder\GeneratingSequence;
use BradynPoulsen\Sequences\SequenceOptions;
use BradynPoulsen\Sequences\Operations\Stateful\{
    DistinctSequence,
    FilteringSequence,
    SortingSequence,
    WindowedSequence
};
use BradynPoulsen\Sequences\Sequence;
use Iterator;
use Traversable;

trait StatefulOperationsTrait
{
    /**
     * @see Sequence::chunked()
     */
    public function chunked(int $size, ?callable $transform = null): Sequence
    {
        return $this->windowed($size, $size, SequenceOptions::INCLUDE_PARTIAL_WINDOWS, $transform);
    }

    /**
     * @see Sequence::distinct()
     */
    public function distinct(): Sequence
    {
        return $this->distinctBy(function ($element) {
            return $element;
        });
    }

    /**
     * @see Sequence::distinctBy()
     */
    public function distinctBy(callable $selector): Sequence
    {
        return new DistinctSequence($this, $selector);
    }

    /**
     * @see Sequence::filter()
     */
    public function filter(callable $predicate): Sequence
    {
        return new FilteringSequence($this, $predicate);
    }

    /**
     * @see Sequence::filterIsInstance()
     */
    public function filterIsInstance(string $type): Sequence
    {
        return $this->filter(function ($element) use ($type): bool {
            return $element instanceof $type;
        });
    }

    /**
     * @see Sequence::filterNot()
     */
    public function filterNot(callable $predicate): Sequence
    {
        return new FilteringSequence($this, $predicate, FilteringSequence::SEND_WHEN_FALSE);
    }

    /**
     * @see Sequence::minus()
     */
    public function minus(iterable $elements): Sequence
    {
        $sequence = new GeneratingSequence(function () use ($elements): Traversable {
            $other = is_array($elements) ? $elements : iterator_to_array($elements);
            return $this->filterNot(function ($element) use ($other): bool {
                return in_array($element, $other);
            })->getIterator();
        });
        return $elements instanceof Iterator ? $sequence->constrainOnce() : $sequence;
    }

    /**
     * @see Sequence::sorted()
     */
    public function sorted(): Sequence
    {
        return new SortingSequence($this, SortingSequence::PHP_BUILTIN_COMPARATOR, SortingSequence::SORT_ASCENDING);
    }

    /**
     * @see Sequence::sortedBy()
     */
    public function sortedBy(callable $selector): Sequence
    {
        return new SortingSequence($this, SortingSequence::compareBy($selector), SortingSequence::SORT_ASCENDING);
    }

    /**
     * @see Sequence::sortedByDescending()
     */
    public function sortedByDescending(callable $selector): Sequence
    {
        return new SortingSequence($this, SortingSequence::compareBy($selector), SortingSequence::SORT_DESCENDING);
    }

    /**
     * @see Sequence::sortedDescending()
     */
    public function sortedDescending(): Sequence
    {
        return new SortingSequence($this, SortingSequence::PHP_BUILTIN_COMPARATOR, SortingSequence::SORT_DESCENDING);
    }

    /**
     * @see Sequence::sortedWith()
     */
    public function sortedWith(callable $comparator): Sequence
    {
        return new SortingSequence($this, $comparator, SortingSequence::SORT_ASCENDING);
    }

    /**
     * @see Sequence::sortedWithDescending()
     */
    public function sortedWithDescending(callable $comparator): Sequence
    {
        return new SortingSequence($this, $comparator, SortingSequence::SORT_DESCENDING);
    }

    /**
     * @see Sequence::windowed()
     */
    public function windowed(
        int $size,
        int $step = 1,
        bool $partialWindows = SequenceOptions::NO_PARTIAL_WINDOWS,
        ?callable $transform = null
    ): Sequence {
        $sequence = new WindowedSequence(
            $this,
            $size,
            $step,
            $partialWindows
        );
        return $transform !== null ? $sequence->map($transform) : $sequence;
    }
}
