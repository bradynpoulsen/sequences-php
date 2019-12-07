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
        /**
         * @SuppressWarnings("unused")
         */
        try {
            return self::first($source, $predicate);
        } catch (UnexpectedValueException $missing) {
            return null;
        }
    }

    /**
     * @see Sequence::indexOfFirst()
     *
     * @param callable $predicate (T) -> bool
     */
    public static function indexOfFirst(Sequence $source, callable $predicate): int
    {
        foreach ($source as $index => $element) {
            if (call_user_func($predicate, $element)) {
                return $index;
            }
        }
        return -1;
    }

    /**
     * @see Sequence::indexOfLast()
     *
     * @param callable $predicate (T) -> bool
     */
    public static function indexOfLast(Sequence $source, callable $predicate): int
    {
        $lastIndex = -1;
        foreach ($source as $index => $element) {
            if (call_user_func($predicate, $element)) {
                $lastIndex = $index;
            }
        }
        return $lastIndex;
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
        /**
         * @SuppressWarnings("unused")
         */
        try {
            return self::last($source, $predicate);
        } catch (UnexpectedValueException $missing) {
            return null;
        }
    }

    /**
     * @see Sequence::single()
     *
     * @param callable|null $predicate (T) -> bool
     */
    public static function single(Sequence $source, ?callable $predicate = null)
    {
        $foundElement = Nothing::get();
        foreach ($source->getIterator() as $element) {
            if (call_user_func($predicate, $element)) {
                if ($foundElement !== Nothing::get()) {
                    throw new OverflowException("Expected only one element matching given predicate");
                }
                $foundElement = $element;
            }
        }
        if ($foundElement !== Nothing::get()) {
            return $foundElement;
        }
        throw new UnexpectedValueException("Expected exactly one element matching given predicate");
    }

    /**
     * @see Sequence::singleOrNull()
     */
    public static function singleOrNull(Sequence $source, ?callable $predicate = null)
    {
        try {
            return self::single($source, $predicate);
        } catch (OverflowException | UnexpectedValueException $exception) {
            return null;
        }
    }
}
