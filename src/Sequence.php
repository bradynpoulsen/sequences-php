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
     * @return bool Sequence<T> -> bool
     */
    public function any(?callable $predicate = null): bool;

    /**
     * Returns an associative array containing key-value pairs provided by $transform function applied to elements of
     * this sequence.
     *
     * @effect terminal
     *
     * @param callable $transform (T) -> [K, V]
     * @return array Sequence<T> -> [K => V]
     */
    public function associate(callable $transform): array;

    /**
     * Returns an associative array containing the values provided by $valueSelector and indexed by $keySelector
     * functions applied to elements of this sequence.
     *
     * If $valueSelector is not provided, the element will be used.
     *
     * @effect terminal
     *
     * @param callable $keySelector (T) -> K
     * @param callable|null $valueSelector (T) -> V
     * @return array Sequence<T> -> [K => V]
     */
    public function associateBy(callable $keySelector, ?callable $valueSelector = null): array;

    /**
     * Returns an associative array where keys are elements from the given sequence and values are produced by the
     * $valueSelector function applied to each element.
     *
     * @effect terminal
     *
     * @param callable $valueSelector (T) -> V
     * @return array Sequence<T> -> [T => V]
     */
    public function associateWith(callable $valueSelector): array;

    /**
     * Returns an average value of elements in this sequence.
     *
     * @effect terminal
     *
     * @return float Sequence<T> -> float
     */
    public function average(): float;

    /**
     * Returns the average value of all values produced by $selector function applied to each element in this sequence.
     *
     * @effect terminal
     *
     * @param callable $selector (T) -> int|float
     * @return float Sequence<T> -> float
     */
    public function averageBy(callable $selector): float;

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
     * @param callable|null $transform (T[] $chunk) -> R
     * @return Sequence Sequence<T> -> Sequence<R>
     */
    public function chunked(int $size, ?callable $transform = null): Sequence;

    /**
     * Returns a sequence containing only distinct elements from this sequence.
     *
     * @effect intermediate
     * @state stateful
     *
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function distinct(): Sequence;

    /**
     * Returns a sequence containing only elements from the given sequence having distinct keys returned
     * by the given selector function.
     *
     * @effect intermediate
     * @state stateful
     *
     * @param callable $selector (T) -> K
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function distinctBy(callable $selector): Sequence;

    /**
     * Returns a sequence containing all elements except first $count elements.
     *
     * @effect intermediate
     * @state stateless
     *
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function drop(int $count): Sequence;

    /**
     * Returns a sequence containing all elements except first elements that satisfy the given $predicate.
     *
     * @effect intermediate
     * @state stateless
     *
     * @param callable $predicate (T) -> bool
     * @return Sequence
     */
    public function dropWhile(callable $predicate): Sequence;

    /**
     * Returns a sequence contain all elements of this sequence that match the provided $predicate.
     *
     * The index of an element may be obtained by accepting a 2nd argument in the $predicate function.
     *
     * @effect intermediate
     * @state stateful
     *
     * @param callable $predicate (T $element[, int $index]) -> bool
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function filter(callable $predicate): Sequence;

    /**
     * Returns a sequence containing all elements of this sequence that are instances of the provided object $type.
     *
     * @effect intermediate
     * @state stateful
     *
     * @param string $type <R> The FQCN of the desired type.
     * @return Sequence Sequence<T> -> Sequence<R>
     */
    public function filterIsInstance(string $type): Sequence;

    /**
     * Returns a sequence contain all elements of this sequence that DO NOT match the provided $predicate.
     *
     * The index of an element may be obtained by accepting a 2nd argument in the $predicate function.
     *
     * @effect intermediate
     * @state stateful
     *
     * @param callable $predicate (T $element[, int $index]) -> bool
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function filterNot(callable $predicate): Sequence;

    /**
     * Returns a single sequence of all elements from results of the provided $transform function being invoked
     * on each element of the original sequence.
     *
     * @effect intermediate
     * @state stateless
     *
     * @param callable $transform (T $element[, int $index]) -> iterable<R>
     * @return Sequence Sequence<T> -> Sequence<R>
     */
    public function flatMap(callable $transform): Sequence;

    /**
     * Returns a single sequence of all elements from each elements of the original sequence.
     *
     * @effect intermediate
     * @state stateless
     *
     * @return Sequence Sequence<iterable<R>> -> Sequence<R>
     */
    public function flatten(): Sequence;

    /**
     * Groups values returned by the $valueTransform function, if given, applied to each element of the this
     * sequence by the key returned by the given $keySelector function applied to the element and returns an
     * associative array where each group key is associated with a list of corresponding values.
     *
     * @effect terminal
     *
     * @param callable $keySelector (T) -> K
     * @param callable|null $valueTransform (T) -> V
     * @return array [K => V[]]
     */
    public function groupBy(callable $keySelector, ?callable $valueTransform = null): array;

    /**
     * Returns a sequence that iterates through the elements either of this sequence or, if this sequence turns
     * out to be empty, of the sequence returned by the provided $supplier function.
     *
     * @effect intermediate
     * @state stateless
     *
     * @param callable $supplier () -> Sequence<B>
     * @return Sequence Sequence<A> -> Sequence<A|B>
     */
    public function ifEmpty(callable $supplier): Sequence;

    /**
     * Returns a sequence containing the results of applying the given $transform function to each element of
     * this sequence.
     *
     * The index of an element may be obtained by accepting a 2nd argument in the $transform function.
     *
     * @effect intermediate
     * @state stateless
     *
     * @param callable $transform (T $element[, int $index]) -> R
     * @return Sequence Sequence<T> -> Sequence<R>
     */
    public function map(callable $transform): Sequence;

    /**
     * Returns a sequence containing all elements of original sequence except the given $elements.
     *
     * If the provided $elements is an Iterator, the resulting sequence will be constrained to being iterated
     * only once. See {@see Sequence::constrainOnce()}.
     *
     * @effect intermediate
     * @state stateful
     *
     * @param iterable $elements iterable<B>
     * @return Sequence Sequence<A|B> -> Sequence<A>
     */
    public function minus(iterable $elements): Sequence;

    /**
     * Returns `true` if no element matches the given $predicate. If no $predicate is specified, returns `true` if
     * this sequence contains no elements.
     *
     * @effect terminal
     *
     * @param callable|null $predicate (T) -> bool
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
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function onEach(callable $action): Sequence;

    /**
     * Returns a sequence containing all elements of original sequence and then all elements of the given $elements.
     *
     * If the provided $elements is an Iterator, the resulting sequence will be constrained to being iterated
     * only once. See {@see Sequence::constrainOnce()}.
     *
     * @effect intermediate
     * @state stateless
     *
     * @param iterable $elements iterable<B>
     * @return Sequence Sequence<A> -> Sequence<A|B>
     */
    public function plus(iterable $elements): Sequence;

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
     * Returns a sequence that yields elements of this sequence sorted according to their natural sort order.
     *
     * @effect intermediate
     * @effect stateful
     *
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function sorted(): Sequence;

    /**
     * Returns a sequence that yields elements of this sequence sorted according to natural sort order of the
     * value returned by specified $selector function.
     *
     * @effect intermediate
     * @effect stateful
     *
     * @param callable $selector (T) -> K
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function sortedBy(callable $selector): Sequence;

    /**
     * Returns a sequence that yields elements of this sequence sorted descending according to natural sort order of the
     * value returned by specified $selector function.
     *
     * @effect intermediate
     * @effect stateful
     *
     * @param callable $selector (T) -> K
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function sortedByDescending(callable $selector): Sequence;

    /**
     * Returns a sequence that yields elements of this sequence sorted descending according to their natural sort order.
     *
     * @effect intermediate
     * @effect stateful
     *
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function sortedDescending(): Sequence;

    /**
     * Returns a sequence that yields elements of this sequence sorted according to the specified $comparator.
     *
     * @effect intermediate
     * @effect stateful
     *
     * @param callable $comparator (T $a, T $b) -> int
     * @return Sequence Sequence<T> -> Sequence<T>
     *
     * @see usort() for an example of how the $comparator must behave
     */
    public function sortedWith(callable $comparator): Sequence;

    /**
     * Returns a sequence that yields elements of this sequence sorted descending according to the
     * specified $comparator.
     *
     * @effect intermediate
     * @effect stateful
     *
     * @param callable $comparator (T $a, T $b) -> int
     * @return Sequence Sequence<T> -> Sequence<T>
     *
     * @see usort() for an example of how the $comparator must behave
     */
    public function sortedWithDescending(callable $comparator): Sequence;

    /**
     * Returns the sum of all elements in this sequence.
     *
     * @effect terminal
     *
     * @return int|float Sequence<T> -> int|float
     */
    public function sum();

    /**
     * Returns the sum of all values produced by $selector function applied to each element in this sequence.
     *
     * @effect terminal
     *
     * @param callable $selector (T) -> int|float
     * @return int|float Sequence<T> -> int|float
     */
    public function sumBy(callable $selector);

    /**
     * Returns a sequence containing first $count elements.
     *
     * @effect intermediate
     * @state stateless
     *
     * @return Sequence
     */
    public function take(int $count): Sequence;

    /**
     * Returns a sequence containing first elements satisfying the given $predicate.
     *
     * @effect intermediate
     * @state stateless
     *
     * @param callable $predicate (T) -> bool
     * @return Sequence
     */
    public function takeWhile(callable $predicate): Sequence;

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
