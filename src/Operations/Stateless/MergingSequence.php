<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Operations\Stateless;

use BradynPoulsen\Sequences\DeferredIterator;
use BradynPoulsen\Sequences\Sequence;
use BradynPoulsen\Sequences\Traits\CommonOperationsTrait;
use Generator;
use Traversable;

/**
 * @internal
 */
final class MergingSequence implements Sequence
{
    use CommonOperationsTrait;

    /**
     * @var Sequence
     */
    private $previous;

    /**
     * @var iterable
     */
    private $additional;

    public function __construct(Sequence $previous, iterable $additional)
    {
        $this->previous = $previous;
        $this->additional = $additional;
    }

    public function getIterator(): Traversable
    {
        return new DeferredIterator(function (): Generator {
            yield from $this->previous->getIterator();
            yield from $this->additional;
        });
    }
}
