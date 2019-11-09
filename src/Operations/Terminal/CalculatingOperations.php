<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Operations\Terminal;

use BradynPoulsen\Sequences\Iteration\Iterations;
use BradynPoulsen\Sequences\Sequence;
use InvalidArgumentException;
use LengthException;

/**
 * @internal
 */
final class CalculatingOperations
{
    private function __construct()
    {
    }

    /**
     * @see Sequence::average()
     */
    public static function average(Sequence $source): float
    {
        $sum = 0.0;
        $count = 0;
        foreach ($source->getIterator() as $element) {
            self::validateNumber($element, "Element must be an integer or float");
            $sum += $element;
            $count++;
        }
        return ($count > 0) ? $sum / $count : $sum;
    }

    /**
     * @see Sequence::averageBy()
     *
     * @param callable $selector (T) -> int|float
     */
    public static function averageBy(Sequence $source, callable $selector): float
    {
        $sum = 0.0;
        $count = 0;
        foreach ($source->getIterator() as $element) {
            $element = call_user_func($selector, $element);
            self::validateNumber($element, "Selector must return an integer or float");
            $sum += $element;
            $count++;
        }
        return ($count > 0) ? $sum / $count : $sum;
    }

    /**
     * @see Sequence::fold()
     *
     * @param callable $operation (R $acc, T $element[, int $index]) -> R
     */
    public static function fold(Sequence $source, $initial, callable $operation)
    {
        $accumulated = $initial;
        foreach ($source->getIterator() as $index => $element) {
            $accumulated = call_user_func($operation, $accumulated, $element, $index);
        }
        return $accumulated;
    }

    /**
     * @see Sequence::reduce()
     *
     * @param callable $operation (R $acc, T $element[, int $index]) -> R
     */
    public static function reduce(Sequence $source, callable $operation)
    {
        $iteration = Iterations::fromTraversable($source->getIterator());
        if (!$iteration->hasNext()) {
            throw new LengthException("Cannot reduce on empty sequence");
        }
        $accumulated = $iteration->pluckNext();
        foreach ($iteration as $index => $element) {
            $accumulated = call_user_func($operation, $accumulated, $element, $index);
        }
        return $accumulated;
    }

    /**
     * @see Sequence::sum()
     */
    public static function sum(Sequence $source)
    {
        $sum = 0;
        foreach ($source->getIterator() as $element) {
            self::validateNumber($element, "Element must be an integer or float");
            $sum += $element;
        }
        return $sum;
    }

    /**
     * @see Sequence::sumBy()
     *
     * @param callable $selector (T) -> int|float
     */
    public static function sumBy(Sequence $source, callable $selector)
    {
        $sum = 0;
        foreach ($source->getIterator() as $element) {
            $element = call_user_func($selector, $element);
            self::validateNumber($element, "Selector must return an integer or float");
            $sum += $element;
        }
        return $sum;
    }

    private static function validateNumber($element, string $errorMessage): void
    {
        if (!is_integer($element) && !is_float($element)) {
            throw new InvalidArgumentException($errorMessage);
        }
    }
}
