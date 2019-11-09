<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Operations\Terminal;

use BradynPoulsen\Sequences\Operations\Stateful\SortingSequence;
use BradynPoulsen\Sequences\Sequence;

/**
 * @internal
 */
final class ElementComparingOperations
{
    private function __construct()
    {
    }

    /**
     * @see Sequence::max()
     */
    public static function max(Sequence $source)
    {
        $largestElement = null;
        foreach ($source->getIterator() as $element) {
            if ($largestElement === null || $element > $largestElement) {
                $largestElement = $element;
            }
        }
        return $largestElement;
    }

    /**
     * @see Sequence::maxBy()
     *
     * @param callable $selector (T) -> R
     */
    public static function maxBy(Sequence $source, callable $selector)
    {
        $comparator = SortingSequence::compareBy($selector);
        return self::maxWith($source, $comparator);
    }

    /**
     * @see Sequence::maxWith()
     *
     * @param callable $comparator (T $a, T $b) -> int
     */
    public static function maxWith(Sequence $source, callable $comparator)
    {
        $largestElement = null;
        foreach ($source->getIterator() as $element) {
            if ($largestElement === null || call_user_func($comparator, $element, $largestElement) > 0) {
                $largestElement = $element;
            }
        }
        return $largestElement;
    }

    /**
     * @see Sequence::min()
     */
    public static function min(Sequence $source)
    {
        $smallestElement = null;
        foreach ($source->getIterator() as $element) {
            if ($smallestElement === null || $element < $smallestElement) {
                $smallestElement = $element;
            }
        }
        return $smallestElement;
    }

    /**
     * @see Sequence::minBy()
     *
     * @param callable $selector (T) -> R
     */
    public static function minBy(Sequence $source, callable $selector)
    {
        $comparator = SortingSequence::compareBy($selector);
        return self::minWith($source, $comparator);
    }

    /**
     * @see Sequence::minWith()
     *
     * @param callable $comparator (T $a, T $b) -> int
     */
    public static function minWith(Sequence $source, callable $comparator)
    {
        $smallestElement = null;
        foreach ($source->getIterator() as $element) {
            if ($smallestElement === null || call_user_func($comparator, $element, $smallestElement) < 0) {
                $smallestElement = $element;
            }
        }
        return $smallestElement;
    }
}
