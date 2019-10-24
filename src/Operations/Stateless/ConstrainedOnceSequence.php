<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Operations\Stateless;

use BradynPoulsen\Sequences\Sequence;
use BradynPoulsen\Sequences\SequenceAlreadyIteratedException;
use BradynPoulsen\Sequences\Traits\CommonOperationsTrait;
use Traversable;

/**
 * @internal
 */
final class ConstrainedOnceSequence implements Sequence
{
    use CommonOperationsTrait;

    /**
     * @var Sequence|null
     */
    private $previous;

    public function __construct(Sequence $previous)
    {
        $this->previous = $previous;
    }

    public function getIterator(): Traversable
    {
        if (null === $this->previous) {
            throw new SequenceAlreadyIteratedException($this);
        }

        $iterator = $this->previous->getIterator();
        $this->previous = null;
        return $iterator;
    }
}
