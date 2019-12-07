<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Operations\Stateless;

use BradynPoulsen\Sequences\Operations\DeferredIterator;
use BradynPoulsen\Sequences\Iteration\Iteration;
use BradynPoulsen\Sequences\Iteration\Iterations;
use BradynPoulsen\Sequences\Sequence;
use BradynPoulsen\Sequences\Traits\CommonOperationsTrait;
use Traversable;

/**
 * @internal
 */
final class DropWhileSequence implements Sequence
{
    use CommonOperationsTrait;

    /**
     * @var Sequence
     */
    private $previous;

    /**
     * @var callable (T) -> bool
     */
    private $predicate;

    public function __construct(Sequence $previous, callable $predicate)
    {
        $this->previous = $previous;
        $this->predicate = $predicate;
    }

    public function getIterator(): Traversable
    {
        return new DeferredIterator(function (): Iteration {
            $iteration = Iterations::fromTraversable($this->previous->getIterator());
            while ($iteration->hasNext() && call_user_func($this->predicate, $iteration->current())) {
                $iteration->pluckNext();
            }
            return $iteration;
        });
    }
}
