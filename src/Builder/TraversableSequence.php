<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Builder;

use BradynPoulsen\Sequences\Sequence;
use BradynPoulsen\Sequences\SequenceAlreadyIteratedException;
use BradynPoulsen\Sequences\Traits\CommonOperationsTrait;
use Traversable;

use function BradynPoulsen\Sequences\sequenceFrom;

/**
 * @internal
 */
final class TraversableSequence implements Sequence
{
    use CommonOperationsTrait;

    /**
     * @var Traversable|null
     */
    private $source;

    /**
     * Use {@see sequenceFrom()} instead.
     *
     * @deprecated Use sequenceFrom() instead.
     */
    public function __construct(Traversable $source)
    {
        $this->source = $source;
    }

    public function getIterator(): Traversable
    {
        if (null === $this->source) {
            throw new SequenceAlreadyIteratedException($this);
        }

        $source = $this->source;
        $this->source = null;
        return $source;
    }
}
