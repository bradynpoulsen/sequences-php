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
     * @see Sequence::associate()
     *
     * @param callable $transform (T) -> [K, V]
     * @return mixed[] [K => V]
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

    /**
     * @see Sequence::associateBy()
     *
     * @param callable $keySelector (T) -> K
     * @param callable|null $valueSelector (T) -> V
     * @return mixed[] [K => V]
     */
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

    /**
     * @see Sequence::associateWith()
     *
     * @param callable $valueSelector (T) -> V
     * @return mixed[] [T => V]
     */
    public static function associateWith(Sequence $source, callable $valueSelector): array
    {
        $result = [];
        foreach ($source->getIterator() as $element) {
            $result[$element] = call_user_func($valueSelector, $element);
        }
        return $result;
    }

    /**
     * @see Sequence::groupBy()
     *
     * @param callable $keySelector (T) -> K
     * @param callable|null $valueTransform (T) -> V
     * @return mixed[][] [K => V[]]
     */
    public static function groupBy(Sequence $source, callable $keySelector, ?callable $valueTransform = null): array
    {
        $result = [];
        foreach ($source->getIterator() as $element) {
            $key = call_user_func($keySelector, $element);
            if (!array_key_exists($key, $result)) {
                $result[$key] = [];
            }
            $value = $valueTransform !== null
                ? call_user_func($valueTransform, $element)
                : $element;
            array_push($result[$key], $value);
        }
        return $result;
    }
}
