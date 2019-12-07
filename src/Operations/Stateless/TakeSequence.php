<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Operations\Stateless;

use BradynPoulsen\Sequences\Operations\DeferredIterator;
use BradynPoulsen\Sequences\Sequence;
use BradynPoulsen\Sequences\Traits\CommonOperationsTrait;
use Generator;
use InvalidArgumentException;
use Traversable;

use function BradynPoulsen\Sequences\emptySequence;

/**
 * @internal
 */
final class TakeSequence implements Sequence
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
            $previous = $this->previous->getIterator();
            foreach ($previous as $element) {
                yield $element;
                $left--;
                if ($left <= 0) {
                    break;
                }
            }
            $previous = null;
        });
    }

    public function drop(int $count): Sequence
    {
        if ($count < 0) {
            throw new InvalidArgumentException("count must be non-negative, but was $count");
        } elseif ($count === 0) {
            return $this;
        }
        return $count >= $this->count
            ? emptySequence()
            : new SubSequence($this->previous, $count, $this->count);
    }

    public function take(int $count): Sequence
    {
        if ($count < 0) {
            throw new InvalidArgumentException("count must be non-negative, but was $count");
        } elseif ($count === 0) {
            return emptySequence();
        } elseif ($count < $this->count) {
            $this->count = $count;
        }
        return $this;
    }
}
