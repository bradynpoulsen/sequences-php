<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Builder;

use BradynPoulsen\Sequences\Sequence;
use InvalidArgumentException;
use Traversable;

use function BradynPoulsen\Sequences\sequenceFrom;

/**
 * @internal
 */
final class GeneratingSequence implements Sequence
{
    /**
     * @var callable
     */
    private $factory;

    /**
     * Use {@see sequenceFrom()} instead.
     *
     * @deprecated Use sequenceFrom() instead.
     */
    public function __construct(callable $factory)
    {
        $this->factory = $factory;
    }

    public function getIterator(): Traversable
    {
        $generator = call_user_func($this->factory);
        if ($generator instanceof Traversable) {
            return $generator;
        }

        throw new InvalidArgumentException("Expected Generator or other Traversable when generating sequence");
    }
}
