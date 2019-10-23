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
     */
    public static function any(Sequence $sequence, ?callable $predicate = null): bool
    {
        $predicate = $predicate ?? function () {
            return true;
        };

        foreach ($sequence as $element) {
            if (call_user_func($predicate, $element)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @see Sequence::none()
     */
    public static function none(Sequence $sequence, ?callable $predicate = null): bool
    {
        $predicate = $predicate ?? function () {
            return true;
        };

        foreach ($sequence as $element) {
            if (call_user_func($predicate, $element)) {
                return false;
            }
        }

        return true;
    }
}
