<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Operations\Stateful;

use BradynPoulsen\Sequences\DeferredIterator;
use BradynPoulsen\Sequences\Sequence;
use BradynPoulsen\Sequences\SequenceAlreadyIteratedException;
use BradynPoulsen\Sequences\Traits\CommonOperationsTrait;
use Generator;
use Traversable;

/**
 * @internal
 */
final class DistinctSequence implements Sequence
{
    use CommonOperationsTrait;

    /**
     * @var Sequence
     */
    private $previous;
    /**
     * @var callable
     */
    private $selector;

    public function __construct(Sequence $previous, callable $selector)
    {
        $this->previous = $previous;
        $this->selector = $selector;
    }

    public function getIterator(): Traversable
    {
        return new DeferredIterator(function (): Generator {
            $seenKeys = [];
            $elements = $this->previous->getIterator();
            foreach ($elements as $element) {
                $key = call_user_func($this->selector, $element);
                if (!in_array($key, $seenKeys)) {
                    yield $element;
                    array_push($seenKeys, $key);
                }
            }
        });
    }
}
