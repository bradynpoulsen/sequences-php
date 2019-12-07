<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Operations\Stateless;

use BradynPoulsen\Sequences\DeferredIterator;
use BradynPoulsen\Sequences\Iteration\Iterations;
use BradynPoulsen\Sequences\Sequence;
use BradynPoulsen\Sequences\Traits\CommonOperationsTrait;
use Generator;
use Traversable;

/**
 * @internal
 */
final class ZippingSequence implements Sequence
{
    use CommonOperationsTrait;

    /**
     * @var Sequence
     */
    private $previous;

    /**
     * @var Sequence
     */
    private $other;

    /**
     * @var callable|null (A $a, B $b) -> R
     */
    private $transform;

    public function __construct(Sequence $previous, Sequence $other, ?callable $transform = null)
    {
        $this->previous = $previous;
        $this->other = $other;
        $this->transform = $transform ?? function ($first, $second): array {
            return [$first, $second];
        };
    }

    public function getIterator(): Traversable
    {
        return new DeferredIterator(function (): Generator {
            $previous = Iterations::fromTraversable($this->previous->getIterator());
            $other = Iterations::fromTraversable($this->other->getIterator());

            while ($previous->hasNext() && $other->hasNext()) {
                yield call_user_func($this->transform, $previous->pluckNext(), $other->pluckNext());
            }
        });
    }
}
