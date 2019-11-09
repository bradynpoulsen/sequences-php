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
final class ZippingWithNextSequence implements Sequence
{
    use CommonOperationsTrait;

    /**
     * @var Sequence
     */
    private $previous;

    /**
     * @var callable (T $a, T $b) -> R
     */
    private $transform;

    public function __construct(Sequence $previous, ?callable $transform = null)
    {
        $this->previous = $previous;
        $this->transform = $transform ?? function ($a, $b): array {
            return [$a, $b];
        };
    }

    public function getIterator(): Traversable
    {
        return new DeferredIterator(function (): Generator {
            $iteration = Iterations::fromTraversable($this->previous->getIterator());
            if (!$iteration->hasNext()) {
                return;
            }
            $current = $iteration->pluckNext();
            while ($iteration->hasNext()) {
                $next = $iteration->pluckNext();
                yield call_user_func($this->transform, $current, $next);
                $current = $next;
            }
        });
    }
}
