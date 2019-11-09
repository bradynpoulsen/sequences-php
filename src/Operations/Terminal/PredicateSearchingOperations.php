<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Operations\Terminal;

use BradynPoulsen\Sequences\Operations\Nothing;
use BradynPoulsen\Sequences\Sequence;
use OverflowException;
use UnexpectedValueException;

/**
 * @internal
 */
final class PredicateSearchingOperations
{
    private function __construct()
    {
    }

    /**
     * @see Sequence::count()
     *
     * @param callable|null $predicate (T) -> bool
     */
    public static function count(Sequence $source, ?callable $predicate = null): int
    {
        $count = 0;
        foreach ($source->getIterator() as $element) {
            if ($predicate === null || call_user_func($predicate, $element)) {
                $count++;
            }
        }
        return $count;
    }
}
