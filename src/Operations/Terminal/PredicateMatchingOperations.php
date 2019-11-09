<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Operations\Terminal;

use BradynPoulsen\Sequences\Sequence;

/**
 * @internal
 */
final class PredicateMatchingOperations
{
    private function __construct()
    {
    }

    /**
     * @see Sequence::all()
     *
     * @param callable $predicate (T) -> bool
     */
    public static function all(Sequence $sequence, callable $predicate): bool
    {
        foreach ($sequence as $element) {
            if (!call_user_func($predicate, $element)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @see Sequence::any()
     *
     * @param callable|null $predicate (T) -> bool
     */
    public static function any(Sequence $sequence, ?callable $predicate = null): bool
    {
        foreach ($sequence as $element) {
            if ($predicate === null || call_user_func($predicate, $element)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @see Sequence::none()
     *
     * @param callable|null $predicate (T) -> bool
     */
    public static function none(Sequence $sequence, ?callable $predicate = null): bool
    {
        foreach ($sequence as $element) {
            if ($predicate === null || call_user_func($predicate, $element)) {
                return false;
            }
        }

        return true;
    }
}
