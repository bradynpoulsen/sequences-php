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

    /**
     * @see Sequence::elementAt()
     */
    public static function elementAt(Sequence $source, int $index)
    {
        foreach ($source->getIterator() as $existingIndex => $element) {
            if ($existingIndex === $index) {
                return $element;
            }
        }
        throw new OutOfRangeException("provided index is not contained in this sequence: " . $index);
    }

    /**
     * @see Sequence::elementAtOrElse()
     *
     * @param callable $defaultValue (int) -> B
     */
    public static function elementAtOrElse(Sequence $source, int $index, callable $defaultValue)
    {
        try {
            return self::elementAt($source, $index);
        } catch (OutOfRangeException $outOfBounds) {
            return $defaultValue($index);
        }
    }

    /**
     * @see Sequence::elementAtOrNull()
     *
     * @return mixed|null
     */
    public static function elementAtOrNull(Sequence $source, int $index)
    {
        try {
            return self::elementAt($source, $index);
        } catch (OutOfRangeException $outOfBounds) {
            return null;
        }
    }

    /**
     * @see Sequence::indexOf()
     */
    public static function indexOf(Sequence $source, $element): int
    {
        foreach ($source->getIterator() as $index => $existingElement) {
            if ($existingElement === $element) {
                return $index;
            }
        }
        return -1;
    }

    /**
     * @see Sequence::lastIndexOf()
     */
    public static function lastIndexOf(Sequence $source, $element): int
    {
        $lastIndex = -1;
        foreach ($source->getIterator() as $index => $existingElement) {
            if ($existingElement === $element) {
                $lastIndex = $index;
            }
        }
        return $lastIndex;
    }
}
