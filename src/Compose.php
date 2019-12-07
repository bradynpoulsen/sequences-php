<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences;

use ArrayAccess;
use Closure;

final class Compose
{
    /**
     * Returns a selector {@see Closure} that selects an index by $index from a provided array or {@see ArrayAccess}.
     *
     * @param mixed $index name or position of index to be selected
     * @return Closure ({@see array}|{@see ArrayAccess}) -> R
     */
    public static function selectIndex($index): Closure
    {
        return function ($source) use ($index) {
            assert(is_array($source) || $source instanceof ArrayAccess);
            return $source[$index];
        };
    }

    /**
     * Returns a selector {@see Closure} that selects a property by $name from a provided object.
     *
     * @param string $name name of property to be selected
     * @return Closure ({@see object}) -> R
     */
    public static function selectProperty(string $name): Closure
    {
        return function (object $source) use ($name) {
            return $source->{$name};
        };
    }

    private function __construct()
    {
    }
}
