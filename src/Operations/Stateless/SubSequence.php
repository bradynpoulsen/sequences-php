<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Operations\Stateless;

use BradynPoulsen\Sequences\DeferredIterator;
use BradynPoulsen\Sequences\Sequence;
use BradynPoulsen\Sequences\Traits\CommonOperationsTrait;
use Generator;
use InvalidArgumentException;
use Traversable;

use function BradynPoulsen\Sequences\emptySequence;

/**
 * @internal
 */
final class SubSequence implements Sequence
{
    use CommonOperationsTrait;

    /**
     * @var Sequence
     */
    private $previous;

    /**
     * @var int
     */
    private $startIndex;

    /**
     * @var int
     */
    private $endIndexExclusive;

    public function __construct(Sequence $previous, int $startIndex, int $endIndexExclusive)
    {
        if ($startIndex < 0) {
            throw new InvalidArgumentException("startIndex must be non-negative, but was $startIndex");
        }
        if ($endIndexExclusive < 0) {
            throw new InvalidArgumentException("startIndex must be non-negative, but was $endIndexExclusive");
        }
        if ($endIndexExclusive <= $startIndex) {
            throw new InvalidArgumentException(
                "endIndex ($endIndexExclusive) must come after startIndex ($startIndex)"
            );
        }

        $this->previous = $previous;
        $this->startIndex = $startIndex;
        $this->endIndexExclusive = $endIndexExclusive;
    }

    public function getIterator(): Traversable
    {
        return new DeferredIterator(function (): Generator {
            $index = 0;
            $iterator = $this->previous->getIterator();
            foreach ($iterator as $element) {
                if ($index < $this->startIndex) {
                    continue;
                }
                yield $element;
                $index++;
                if ($index >= $this->endIndexExclusive) {
                    break;
                }
            }
        });
    }

    public function drop(int $count): Sequence
    {
        if ($count < 0) {
            throw new InvalidArgumentException("count must be non-negative, but was $count");
        }
        $newStart = $this->startIndex + $count;
        if ($newStart < $this->endIndexExclusive) {
            $this->startIndex = $newStart;
            return $this;
        }
        return emptySequence();
    }

    public function take(int $count): Sequence
    {
        if ($count < 0) {
            throw new InvalidArgumentException("count must be non-negative, but was $count");
        } elseif ($count === 0) {
            return emptySequence();
        }
        $currentCount = $this->endIndexExclusive - $this->startIndex;
        if ($currentCount > $count) {
            $this->endIndexExclusive = $this->startIndex + $count;
        }
        return $this;
    }
}
