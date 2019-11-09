<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Traits;

use BradynPoulsen\Sequences\Operations\Terminal\{
    AssociateOperations,
    CalculatingOperations,
    ElementComparingOperations,
    ElementSearchingOperations,
    PredicateMatchingOperations,
    PredicateSearchingOperations
};
use BradynPoulsen\Sequences\Sequence;

trait TerminalOperationsTrait
{
    /**
     * @see Sequence::all()
     */
    public function all(callable $predicate): bool
    {
        return PredicateMatchingOperations::all($this, $predicate);
    }

    /**
     * @see Sequence::any()
     */
    public function any(?callable $predicate = null): bool
    {
        return PredicateMatchingOperations::any($this, $predicate);
    }

    /**
     * @see Sequence::associate()
     */
    public function associate(callable $transform): array
    {
        return AssociateOperations::associate($this, $transform);
    }

    /**
     * @see Sequence::associateBy()
     */
    public function associateBy(callable $keySelector, ?callable $valueSelector = null): array
    {
        return AssociateOperations::associateBy($this, $keySelector, $valueSelector);
    }

    /**
     * @see Sequence::associateWith()
     */
    public function associateWith(callable $valueSelector): array
    {
        return AssociateOperations::associateWith($this, $valueSelector);
    }

    /**
     * @see Sequence::average()
     */
    public function average(): float
    {
        return CalculatingOperations::average($this);
    }

    /**
     * @see Sequence::averageBy()
     */
    public function averageBy(callable $selector): float
    {
        return CalculatingOperations::averageBy($this, $selector);
    }

    /**
     * @see Sequence::contains()
     */
    public function contains($element): bool
    {
        return ElementSearchingOperations::contains($this, $element);
    }

    /**
     * @see Sequence::count()
     */
    public function count(?callable $predicate = null): int
    {
        return PredicateSearchingOperations::count($this, $predicate);
    }

    /**
     * @see Sequence::elementAt()
     */
    public function elementAt(int $index)
    {
        return ElementSearchingOperations::elementAt($this, $index);
    }

    /**
     * @see Sequence::elementAtOrElse()
     */
    public function elementAtOrElse(int $index, callable $defaultValue)
    {
        return ElementSearchingOperations::elementAtOrElse($this, $index, $defaultValue);
    }

    /**
     * @see Sequence::elementAtOrNull()
     */
    public function elementAtOrNull(int $index)
    {
        return ElementSearchingOperations::elementAtOrNull($this, $index);
    }

    /**
     * @see Sequence::first()
     */
    public function first(?callable $predicate = null)
    {
        return PredicateSearchingOperations::first($this, $predicate);
    }

    /**
     * @see Sequence::firstOrNull()
     */
    public function firstOrNull(?callable $predicate = null)
    {
        return PredicateSearchingOperations::firstOrNull($this, $predicate);
    }

    /**
     * @see Sequence::fold()
     */
    public function fold($initial, callable $operation)
    {
        return CalculatingOperations::fold($this, $initial, $operation);
    }

    /**
     * @see Sequence::groupBy()
     */
    public function groupBy(callable $keySelector, ?callable $valueTransform = null): array
    {
        return AssociateOperations::groupBy($this, $keySelector, $valueTransform);
    }

    /**
     * @see Sequence::indexOf()
     */
    public function indexOf(Sequence $source, $element): int
    {
        return ElementSearchingOperations::indexOf($this, $element);
    }

    /**
     * @see Sequence::indexOfFirst()
     */
    public function indexOfFirst(callable $predicate): int
    {
        return PredicateSearchingOperations::indexOfFirst($this, $predicate);
    }

    /**
     * @see Sequence::indexOfLast()
     */
    public function indexOfLast(callable $predicate): int
    {
        return PredicateSearchingOperations::indexOfLast($this, $predicate);
    }

    /**
     * @see Sequence::last()
     */
    public function last(?callable $predicate = null)
    {
        return PredicateSearchingOperations::last($this, $predicate);
    }

    /**
     * @see Sequence::lastIndexOf()
     */
    public function lastIndexOf(Sequence $source, $element): int
    {
        return ElementSearchingOperations::lastIndexOf($this, $element);
    }

    /**
     * @see Sequence::lastOrNull()
     */
    public function lastOrNull(?callable $predicate = null)
    {
        return PredicateSearchingOperations::lastOrNull($this, $predicate);
    }

    /**
     * @see Sequence::max()
     */
    public function max()
    {
        return ElementComparingOperations::max($this);
    }

    /**
     * @see Sequence::maxBy()
     */
    public function maxBy(callable $selector)
    {
        return ElementComparingOperations::maxBy($this, $selector);
    }

    /**
     * @see Sequence::maxWith()
     */
    public function maxWith(callable $comparator)
    {
        return ElementComparingOperations::maxWith($this, $comparator);
    }

    /**
     * @see Sequence::min()
     */
    public function min()
    {
        return ElementComparingOperations::min($this);
    }

    /**
     * @see Sequence::minBy()
     */
    public function minBy(callable $selector)
    {
        return ElementComparingOperations::minBy($this, $selector);
    }

    /**
     * @see Sequence::minWith()
     */
    public function minWith(callable $comparator)
    {
        return ElementComparingOperations::minWith($this, $comparator);
    }

    /**
     * @see Sequence::none()
     */
    public function none(?callable $predicate = null): bool
    {
        return PredicateMatchingOperations::none($this, $predicate);
    }

    /**
     * @see Sequence::reduce()
     */
    public function reduce(callable $operation)
    {
        return CalculatingOperations::reduce($this, $operation);
    }

    /**
     * @see Sequence::sum()
     */
    public function sum()
    {
        return CalculatingOperations::sum($this);
    }

    /**
     * @see Sequence::sumBy()
     */
    public function sumBy(callable $selector)
    {
        return CalculatingOperations::sumBy($this, $selector);
    }
}
