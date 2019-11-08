<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Operations\Terminal;

use BradynPoulsen\Sequences\Sequence;

/**
 * @internal
 */
final class AssociateOperations
{
    private function __construct()
    {
    }

    /**
     * @param callable $transform (T) -> [K, V]
     */
    public static function associate(Sequence $source, callable $transform): array
    {
        $result = [];
        foreach ($source->getIterator() as $element) {
            list($key, $value) = call_user_func($transform, $element);
            $result[$key] = $value;
        }
        return $result;
    }

    public static function associateBy(Sequence $source, callable $keySelector, ?callable $valueSelector = null): array
    {
        $valueSelector = $valueSelector ?? function ($element) {
            return $element;
        };
        $result = [];
        foreach ($source->getIterator() as $element) {
            $result[call_user_func($keySelector, $element)] = call_user_func($valueSelector, $element);
        }
        return $result;
    }

    public static function associateWith(Sequence $source, callable $valueSelector): array
    {
        $result = [];
        foreach ($source->getIterator() as $element) {
            $result[$element] = call_user_func($valueSelector, $element);
        }
        return $result;
    }
}
