<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences;

use InvalidArgumentException;
use IteratorAggregate;
use OutOfRangeException;
use OverflowException;
use Traversable;
use UnexpectedValueException;

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
     * Returns true if $element is found in this sequence.
     *
     * @effect terminal
     *
     * @param mixed $element The element to search for
     * @return bool Sequence<T> -> bool
     */
    public function contains($element): bool;

    /**
     * Returns the number of elements matching the given $predicate, if provided.
     *
     * @effect terminal
     *
     * @param callable|null $predicate (T) -> bool
     * @return int Sequence<T> -> int
     */
    public function count(?callable $predicate = null): int;

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
     * Returns an element at the given $index or throws an {@see OutOfRangeException} if the index is out of bounds
     * of this sequence.
     *
     * @effect terminal
     *
     * @return mixed Sequence<T> -> T
     * @throws OutOfRangeException if the given index is not contained.
     */
    public function elementAt(int $index);

    /**
     * Returns an element at the given $index or the result of calling the $defaultValue function if the index is out
     * of bounds of this sequence.
     *
     * @effect terminal
     *
     * @param callable $defaultValue (int) -> T
     * @return mixed Sequence<T> -> T
     */
    public function elementAtOrElse(int $index, callable $defaultValue);

    /**
     * Returns an element at the given $index or null if the index is out of bounds of this sequence.
     *
     * @effect terminal
     *
     * @return mixed|null Sequence<T> -> ?T
     */
    public function elementAtOrNull(int $index);

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
     * Returns the first element matching the given $predicate.
     *
     * @effect terminal
     *
     * @param callable|null $predicate (T) -> bool
     * @return mixed Sequence<T> -> T
     * @throws UnexpectedValueException if no elements matched the predicate
     */
    public function first(?callable $predicate = null);

    /**
     * Returns the first element matching the given $predicate, or null if element was not found.
     *
     * @effect terminal
     *
     * @param callable|null $predicate (T) -> bool
     * @return mixed Sequence<T> -> T
     */
    public function firstOrNull(?callable $predicate = null);

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
     * Accumulates value starting with initial value and applying $operation from left to right to current accumulator
     * value and each element with its index in the original sequence.
     *
     * @effect terminal
     *
     * @param mixed $initial <R> The initial accumulator value to use in the $operation.
     * @param callable $operation (R $acc, T $element[, int $index]) -> R
     * @return mixed Sequence<T> -> R
     */
    public function fold($initial, callable $operation);

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
     * Returns first index of $element, or -1 if this sequence does not contain $element.
     *
     * @effect terminal
     *
     * @param mixed $element The element to search for
     * @return int Sequence<T> -> int
     */
    public function indexOf($element): int;

    /**
     * Returns index of the first element matching the given $predicate, or -1 if the sequence does not contain
     * such element.
     *
     * @effect terminal
     *
     * @param callable $predicate (T) -> bool
     * @return int Sequence<T> -> int
     */
    public function indexOfFirst(callable $predicate): int;

    /**
     * Returns index of the last element matching the given $predicate, or -1 if the sequence does not contain
     * such element.
     *
     * @effect terminal
     *
     * @param callable $predicate (T) -> bool
     * @return int Sequence<T> -> int
     */
    public function indexOfLast(callable $predicate): int;

    /**
     * Returns the last element matching the given $predicate.
     *
     * @effect terminal
     *
     * @param callable|null $predicate (T) -> bool
     * @return mixed Sequence<T> -> T
     * @throws UnexpectedValueException if no elements matched the predicate
     */
    public function last(?callable $predicate = null);

    /**
     * Returns last index of $element, or -1 if this sequence does not contain $element.
     *
     * @effect terminal
     *
     * @param mixed $element The element to search for
     * @return int Sequence<T> -> int
     */
    public function lastIndexOf($element): int;

    /**
     * Returns the last element matching the given $predicate, or null if element was not found.
     *
     * @effect terminal
     *
     * @param callable|null $predicate (T) -> bool
     * @return mixed Sequence<T> -> T
     */
    public function lastOrNull(?callable $predicate = null);

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
     * Returns the largest element or null if there are no elements.
     *
     * @effect terminal
     *
     * @return mixed|null Sequence<T> -> ?T
     */
    public function max();

    /**
     * Returns the first element yielding the largest value of the given $selector or null if there are no elements.
     *
     * @effect terminal
     *
     * @param callable $selector (T) -> R
     * @return mixed|null Sequence<T> -> ?T
     */
    public function maxBy(callable $selector);

    /**
     * Returns the first element having the largest value according to the provided $comparator or null if there are
     * no elements.
     *
     * @effect terminal
     *
     * @param callable $comparator (T $a, T $b) -> int
     * @return mixed|null Sequence<T> -> ?T
     */
    public function maxWith(callable $comparator);

    /**
     * Returns the smallest element or null if there are no elements.
     *
     * @effect terminal
     *
     * @return mixed|null Sequence<T> -> ?T
     */
    public function min();

    /**
     * Returns the first element yielding the smallest value of the given $selector or null if there are no elements.
     *
     * @effect terminal
     *
     * @param callable $selector (T) -> R
     * @return mixed|null Sequence<T> -> ?T
     */
    public function minBy(callable $selector);

    /**
     * Returns the first element having the smallest value according to the provided $comparator or null if there are
     * no elements.
     *
     * @effect terminal
     *
     * @param callable $comparator (T $a, T $b) -> int
     * @return mixed|null Sequence<T> -> ?T
     */
    public function minWith(callable $comparator);

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
     * Accumulates value starting with the first element and applying $operation from left to right to current
     * accumulator value and each element with its index in the original sequence.
     *
     * @effect terminal
     *
     * @param callable $operation (R $acc, T $element[, int $index]) -> R
     * @return mixed Sequence<T> -> R
     */
    public function reduce(callable $operation);

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
     * Returns the single element matching the given $predicate, or throws exception if there is no or more than
     * one matching element.
     *
     * @effect terminal
     *
     * @param callable|null $predicate (T) -> bool
     * @return mixed Sequence<T> -> T
     * @throws UnexpectedValueException if no elements matched the predicate
     * @throws OverflowException if more than one element matched the predicate
     */
    public function single(?callable $predicate = null);

    /**
     * Returns the single element matching the given $predicate, or returns null if there is no or more than one
     * matching element.
     *
     * @effect terminal
     *
     * @param callable|null $predicate (T) -> bool
     * @return mixed|null Sequence<T> -> ?T
     */
    public function singleOrNull(?callable $predicate = null);

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
     * Returns a sequence that advances $step elements between each yielded element of this sequence.
     *
     * @example
     *
     *      // sequence of: 1, 5, 21, 89
     *      sequenceFrom('generate_fibonacci')->step(3)->take(4);
     *
     * @effect intermediate
     * @effect stateful
     *
     * @param int $step the number of elements to step across between each yield
     * @return Sequence Sequence<T> -> Sequence<T>
     */
    public function step(int $step): Sequence;

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
     * Collect all of the elements in this sequence into an array.
     *
     * @effect terminal
     *
     * @param callable $transform (T $element[, int $index]) -> R
     * @return array
     */
    public function toArray(?callable $transform = null): array;

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

    /**
     * Returns a sequence of values built from the elements of this sequence and the other sequence with the same index
     * using the provided $transform function applied to each pair of elements, if provided; otherwise as an
     * array [A, B]. The resulting sequence ends as soon as the shortest input sequence ends.
     *
     * @effect intermediate
     * @state stateless
     *
     * @param Sequence $other Sequence<B>
     * @param callable|null $transform (A, B) -> R
     * @return Sequence Sequence<A> -> Sequence<R>
     */
    public function zip(Sequence $other, ?callable $transform = null): Sequence;

    /**
     * Returns a sequence containing the results of applying the given $transform function, if provided, to an each pair
     * of two adjacent elements in this sequence.
     *
     * @effect intermediate
     * @state stateless
     *
     * @param callable|null $transform (T $a, T $b) -> R
     * @return Sequence Sequence<T> -> Sequence<R>
     */
    public function zipWithNext(?callable $transform = null): Sequence;
}
