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
final class DropSequence implements Sequence
{
    use CommonOperationsTrait;

    /**
     * @var Sequence
     */
    private $previous;

    /**
     * @var int
     */
    private $count;

    public function __construct(Sequence $previous, int $count)
    {
        if ($count <= 0) {
            throw new InvalidArgumentException("count must be greater than zero, but was $count");
        }

        $this->previous = $previous;
        $this->count = $count;
    }

    public function getIterator(): Traversable
    {
        return new DeferredIterator(function (): Generator {
            $left = $this->count;
            foreach ($this->previous->getIterator() as $element) {
                if ($left > 0) {
                    $left--;
                    continue;
                }
                yield $element;
            }
        });
    }

    public function drop(int $count): Sequence
    {
        if ($count < 0) {
            throw new InvalidArgumentException("count must be non-negative, but was $count");
        }
        $this->count += $count;
        return $this;
    }

    public function take(int $count): Sequence
    {
        if ($count < 0) {
            throw new InvalidArgumentException("count must be non-negative, but was $count");
        }
        $endIndex = $this->count + $count;
        if ($this->count < $endIndex) {
            return new SubSequence($this->previous, $this->count, $endIndex);
        }
        return emptySequence();
    }
}
