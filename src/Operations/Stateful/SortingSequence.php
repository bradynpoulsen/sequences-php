<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Operations\Stateful;

use BradynPoulsen\Sequences\DeferredIterator;
use BradynPoulsen\Sequences\Sequence;
use BradynPoulsen\Sequences\Traits\CommonOperationsTrait;
use Closure;
use Generator;
use Traversable;

/**
 * @internal
 */
final class SortingSequence implements Sequence
{
    use CommonOperationsTrait;

    public const PHP_BUILTIN_COMPARATOR = null;
    public const SORT_ASCENDING = true;
    public const SORT_DESCENDING = false;

    /**
     * @var Sequence
     */
    private $previous;

    /**
     * @var callable|null (T $a, T $b) -> int
     */
    private $comparator;

    /**
     * @var bool
     */
    private $direction;

    public function __construct(
        Sequence $previous,
        ?callable $comparator,
        bool $direction
    ) {
        $this->previous = $previous;
        $this->comparator = $comparator;
        $this->direction = $direction;
    }

    public function getIterator(): Traversable
    {
        return new DeferredIterator(function (): Generator {
            $elements = iterator_to_array($this->previous->getIterator());
            if ($this->comparator !== self::PHP_BUILTIN_COMPARATOR) {
                if ($this->direction === self::SORT_ASCENDING) {
                    usort($elements, $this->comparator);
                } else {
                    usort($elements, self::reverseComparator($this->comparator));
                }
            } else {
                if ($this->direction === self::SORT_ASCENDING) {
                    sort($elements);
                } else {
                    rsort($elements);
                }
            }
            yield from array_values($elements);
        });
    }

    public static function compareBy(callable $selector): callable
    {
        return function ($first, $second) use ($selector): int {
            $aKey = call_user_func($selector, $first);
            $bKey = call_user_func($selector, $second);
            if ($aKey > $bKey) {
                return 1;
            } elseif ($aKey < $bKey) {
                return -1;
            }
            return 0;
        };
    }

    private static function reverseComparator(callable $comparator): callable
    {
        return function ($first, $second) use ($comparator): int {
            return call_user_func($comparator, $second, $first);
        };
    }
}
