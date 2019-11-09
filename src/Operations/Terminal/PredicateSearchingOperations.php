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

    /**
     * @see Sequence::first()
     *
     * @param callable|null $predicate (T) -> bool
     */
    public static function first(Sequence $source, ?callable $predicate = null)
    {
        foreach ($source->getIterator() as $element) {
            if ($predicate === null || call_user_func($predicate, $element)) {
                return $element;
            }
        }
        throw new UnexpectedValueException("No element matched the given predicate");
    }

    /**
     * @see Sequence::firstOrNull()
     *
     * @param callable|null $predicate (T) -> bool
     */
    public static function firstOrNull(Sequence $source, ?callable $predicate = null)
    {
        try {
            return self::first($source, $predicate);
        } catch (UnexpectedValueException $unexpectedValueException) {
            return null;
        }
    }

    /**
     * @see Sequence::last()
     *
     * @param callable|null $predicate (T) -> bool
     */
    public static function last(Sequence $source, ?callable $predicate = null)
    {
        $lastElement = Nothing::get();
        foreach ($source->getIterator() as $element) {
            if ($predicate === null || call_user_func($predicate, $element)) {
                $lastElement = $element;
            }
        }
        if ($lastElement !== Nothing::get()) {
            return $lastElement;
        }
        throw new UnexpectedValueException("No element matched the given predicate");
    }

    /**
     * @see Sequence::lastOrNull()
     *
     * @param callable|null $predicate (T) -> bool
     */
    public static function lastOrNull(Sequence $source, ?callable $predicate = null)
    {
        try {
            return self::last($source, $predicate);
        } catch (UnexpectedValueException $unexpectedValueException) {
            return null;
        }
    }
}
