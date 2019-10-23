<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences;

use IteratorAggregate;
use Traversable;

/**
 * A sequence of values that can be iterated over. The values are evaluated lazily, and the sequence
 * is potentially infinite.
 *
 * Sequences can be iterated multiple times, unless implementations constrain themselves to be iterated
 * only once. Operations, like map, filter, etc, generally preserved this constraint, and must be
 * documented if it doesn't.
 *
 * Operations must be classified into groups of state requirements and effect.
 *
 * State Requirements:
 *   - @state stateless - operations which require no state and process each element independently.
 *   - @state stateful - operations which require an amount of state, usually proportional to number of elements
 *
 * Effect:
 *   - @effect intermediate - operations that return another sequence, which process each element lazily
 *   - @effect terminal - operations that consume the sequence to return a non-sequence result
 */
interface Sequence extends IteratorAggregate
{
    /**
     * Returns an iterator over the values in this sequence.
     *
     * @effect terminal
     *
     * @throws SequenceAlreadyIteratedException if the sequence is constrained to be iterated only once and
     *      {@see Sequence::getIterator()} is invoked a second time.
     */
    public function getIterator(): Traversable;

    /**
     * Returns a wrapper {@see Sequence} that provides values of this sequence, but ensures it can be iterated only
     * one time.
     *
     * @effect intermediate
     * @state stateless
     *
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function constrainOnce(): Sequence;

    /**
     * Returns a sequence contain all elements of this sequence that match the provided $predicate.
     *
     * @effect intermediate
     * @state stateful
     *
     * @param callable $predicate (T [, int $indexed]) -> bool
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function filter(callable $predicate): Sequence;

    /**
     * Returns a sequence contain all elements of this sequence that DO NOT match the provided $predicate.
     *
     * @effect intermediate
     * @state stateful
     *
     * @param callable $predicate (T [, int $indexed]) -> bool
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function filterNot(callable $predicate): Sequence;

    /**
     * Returns a sequence containing the results of applying the given $transform function to each element of
     * this sequence.
     *
     * @effect intermediate
     * @state stateless
     *
     * @param callable $transform (T [, int $index]) -> R
     *
     * @return Sequence Sequence<T> -> Sequence<R>
     */
    public function map(callable $transform): Sequence;
}
