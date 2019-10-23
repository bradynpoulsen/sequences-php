<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Builder;

use ArrayIterator;
use BradynPoulsen\Sequences\Sequence;
use BradynPoulsen\Sequences\Traits\CommonOperationsTrait;
use Traversable;

use function BradynPoulsen\Sequences\sequenceOf;

/**
 * @internal
 */
final class StaticSequence implements Sequence
{
    use CommonOperationsTrait;

    /**
     * @var array
     */
    private $elements;

    /**
     * Use {@see sequenceOf()} instead.
     *
     * @deprecated Use sequenceOf() instead.
     */
    public function __construct(array $elements)
    {
        $this->elements = $elements;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->elements);
    }
}
