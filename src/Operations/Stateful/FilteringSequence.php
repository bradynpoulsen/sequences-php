<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Operations\Stateful;

use BradynPoulsen\Sequences\Iteration\Iteration;
use BradynPoulsen\Sequences\Iteration\IterationBuilder;
use BradynPoulsen\Sequences\Iteration\Iterations;
use BradynPoulsen\Sequences\Sequence;
use BradynPoulsen\Sequences\Traits\CommonOperationsTrait;
use Traversable;

/**
 * @internal
 */
final class FilteringSequence implements Sequence
{
    use CommonOperationsTrait;

    public const SEND_WHEN_TRUE = 0;
    public const SEND_WHEN_FALSE = 1;

    /**
     * @var Sequence
     */
    private $previous;

    /**
     * @var callable
     */
    private $predicate;

    /**
     * @var bool
     */
    private $sendWhen;

    public function __construct(
        Sequence $previous,
        callable $predicate,
        int $sendWhen = self::SEND_WHEN_TRUE
    ) {
        $this->previous = $previous;
        $this->predicate = $predicate;
        $this->sendWhen = self::SEND_WHEN_TRUE === $sendWhen;
    }

    public function getIterator(): Traversable
    {
        return Iterations::buildLinked($this->previous, function (Iteration $previous, IterationBuilder $builder) {
            while ($previous->hasNext()) {
                $element = $previous->pluckNext();
                if (call_user_func($this->predicate, $element, $builder->getIndex()) == $this->sendWhen) {
                    $builder->setNext($element);
                    return;
                }
                $builder->skipping();
            }

            $builder->close();
        });
    }
}
