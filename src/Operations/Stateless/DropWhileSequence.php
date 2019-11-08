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
        return new DeferredIterator(function (): Generator {
            $complete = false;
            foreach ($this->previous->getIterator() as $element) {
                if (!$complete && call_user_func($this->predicate, $element)) {
                    continue;
                }
                $complete = true;
                yield $element;
            }
        });
    }
}
