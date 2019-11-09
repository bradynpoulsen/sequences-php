<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Traits;

use BradynPoulsen\Sequences\Operations\Terminal\{
    AssociateOperations,
    PredicateMatchingOperations
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
     * @see Sequence::groupBy()
     */
    public function groupBy(callable $keySelector, ?callable $valueTransform = null): array
    {
        return AssociateOperations::groupBy($this, $keySelector, $valueTransform);
    }

    /**
     * @see Sequence::none()
     */
    public function none(?callable $predicate = null): bool
    {
        return PredicateMatchingOperations::none($this, $predicate);
    }
}
