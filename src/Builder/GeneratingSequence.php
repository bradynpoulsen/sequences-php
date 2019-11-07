<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Builder;

use BradynPoulsen\Sequences\Sequence;
use BradynPoulsen\Sequences\Traits\CommonOperationsTrait;
use InvalidArgumentException;
use Traversable;

use function BradynPoulsen\Sequences\sequenceFrom;

/**
 * @internal
 */
final class GeneratingSequence implements Sequence
{
    use CommonOperationsTrait;

    /**
     * @var callable () -> Traversable
     */
    private $supplier;

    /**
     * Use {@see sequenceFrom()} instead.
     *
     * @param callable $supplier () -> Traversable
     *
     * @deprecated Use sequenceFrom() instead.
     */
    public function __construct(callable $supplier)
    {
        $this->supplier = $supplier;
    }

    public function getIterator(): Traversable
    {
        $generator = call_user_func($this->supplier);
        if ($generator instanceof Traversable) {
            return $generator;
        }

        throw new InvalidArgumentException("Expected Generator or other Traversable when generating sequence");
    }
}
