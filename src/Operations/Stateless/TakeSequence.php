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
            throw new InvalidArgumentException("count must be greater than zero");
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
}
