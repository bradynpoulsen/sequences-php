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

    private static function validateNumber($element, string $errorMessage): void
    {
        if (!is_integer($element) && !is_float($element)) {
            throw new InvalidArgumentException($errorMessage);
        }
    }
}
