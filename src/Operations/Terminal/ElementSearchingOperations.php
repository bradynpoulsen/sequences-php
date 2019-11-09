<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Operations\Terminal;

use BradynPoulsen\Sequences\Sequence;
use OutOfRangeException;

/**
 * @internal
 */
final class ElementSearchingOperations
{
    private function __construct()
    {
    }

    /**
     * @see Sequence::contains()
     */
    public static function contains(Sequence $source, $element): bool
    {
        foreach ($source->getIterator() as $existingElement) {
            if ($existingElement === $element) {
                return true;
            }
        }

        return false;
    }
}
