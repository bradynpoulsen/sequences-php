<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences;

use InvalidArgumentException;
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
     * Returns `true` if all elements match the given $predicate.
     *
     * @effect terminal
     *
     * @param callable $predicate (T) -> bool
     * @return bool Sequence<T> -> bool
     */
    public function all(callable $predicate): bool;

    /**
     * Returns `true` if at least one element matches the given $predicate. If no $predicate is specified, returns
     * `true` if this sequence contains at least one element.
     *
     * @effect terminal
     *
     * @param callable|null $predicate (T) -> bool
     *
     * @return bool Sequence<T> -> bool
     */
    public function any(?callable $predicate = null): bool;

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
     * Splits this sequence into a sequence of arrays each not exceeding the given $size.
     *
     * The last list in the resulting sequence may have less elements than the given $size.
     *
     * @effect intermediate
     * @state stateful
     *
     * @param callable|null $transform (T[] chunk) -> R
     *
     * @return Sequence Sequence<T> -> Sequence<R>
     */
    public function chunked(int $size, ?callable $transform = null): Sequence;

    /**
     * Returns a sequence contain all elements of this sequence that match the provided $predicate.
     *
     * The index of an element may be obtained by accepting a 2nd argument in the $predicate function.
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
     * The index of an element may be obtained by accepting a 2nd argument in the $predicate function.
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
     * The index of an element may be obtained by accepting a 2nd argument in the $transform function.
     *
     * @effect intermediate
     * @state stateless
     *
     * @param callable $transform (T [, int $index]) -> R
     *
     * @return Sequence Sequence<T> -> Sequence<R>
     */
    public function map(callable $transform): Sequence;

    /**
     * Returns `true` if no element matches the given $predicate. If no $predicate is specified, returns `true` if
     * this sequence contains no elements.
     *
     * @effect terminal
     *
     * @param callable|null $predicate (T) -> bool
     *
     * @return bool Sequence<T> -> bool
     */
    public function none(?callable $predicate = null): bool;

    /**
     * Returns a sequence which performs the given $action on each element of the original sequence.
     *
     * @effect intermediate
     * @state stateless
     *
     * @param callable $action (T) -> void
     *
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function onEach(callable $action): Sequence;

    /**
     * Returns a sequence which validates each element matches the given $predicate.
     * {@see InvalidArgumentException} will be thrown if an element does not match the given $predicate.
     *
     * @effect intermediate
     * @state stateless
     *
     * @param callable $predicate (T) -> bool
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function require(callable $predicate): Sequence;

    /**
     * Returns a sequence which validates each element does not match the given $predicate.
     * {@see InvalidArgumentException} will be thrown if an element matches the given $predicate.
     *
     * @effect intermediate
     * @state stateless
     *
     * @param callable $predicate (T) -> bool
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function requireNot(callable $predicate): Sequence;

    /**
     * Returns a sequence of results of applying the given $transform function to arrays that represent a window
     * of the given $size sliding along this sequence with the given $step.
     *
     * @effect intermediate
     * @state stateful
     *
     * @param int $size the number of elements to take in each window, must be positive
     * @param int $step the number of elements to move the window forward by on each step, must be positive
     * @param bool $partialWindows whether or not to keep partial windows in the end, if any
     * @param callable|null $transform (T[] $window) -> R defaults to returning the window as an array
     *
     * @return Sequence Sequence<T> -> Sequence<R>
     *
     * @see SequenceOptions::INCLUDE_PARTIAL_WINDOWS
     * @see SequenceOptions::NO_PARTIAL_WINDOWS
     */
    public function windowed(
        int $size,
        int $step = 1,
        bool $partialWindows = SequenceOptions::NO_PARTIAL_WINDOWS,
        ?callable $transform = null
    ): Sequence;
}
