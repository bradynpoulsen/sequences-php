<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Operations\Stateless;

use BradynPoulsen\Sequences\DeferredIterator;
use BradynPoulsen\Sequences\Sequence;
use BradynPoulsen\Sequences\Traits\CommonOperationsTrait;
use Generator;
use Traversable;

/**
 * @internal
 */
final class TransformingSequence implements Sequence
{
    use CommonOperationsTrait;

    /**
     * @var Sequence
     */
    private $previous;

    /**
     * @var callable
     */
    private $transform;

    public function __construct(Sequence $previous, callable $transform)
    {
        $this->previous = $previous;
        $this->transform = $transform;
    }

    public function getIterator(): Traversable
    {
        return new DeferredIterator(function (): Generator {
            $index = 0;
            $elements = $this->previous->getIterator();
            foreach ($elements as $element) {
                yield call_user_func($this->transform, $element, $index++);
            }
        });
    }
}
