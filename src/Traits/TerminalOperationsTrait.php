<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Traits;

use BradynPoulsen\Sequences\Operations\Terminal\PredicateMatchingOperations;
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
     * @see Sequence::none()
     */
    public function none(?callable $predicate = null): bool
    {
        return PredicateMatchingOperations::none($this, $predicate);
    }
}
