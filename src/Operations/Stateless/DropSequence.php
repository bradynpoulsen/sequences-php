<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Operations\Stateless;

use BradynPoulsen\Sequences\DeferredIterator;
use BradynPoulsen\Sequences\Sequence;
use BradynPoulsen\Sequences\Traits\CommonOperationsTrait;
use Generator;
use InvalidArgumentException;
use Traversable;

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
        if ($count < 0) {
            throw new InvalidArgumentException("count must be non-negative, but was $count");
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
        return new self($this->previous, $this->count + $count);
    }
}
