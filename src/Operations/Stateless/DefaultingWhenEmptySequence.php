<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Operations\Stateless;

use BradynPoulsen\Sequences\Operations\DeferredIterator;
use BradynPoulsen\Sequences\Iteration\Iterations;
use BradynPoulsen\Sequences\Sequence;
use BradynPoulsen\Sequences\Traits\CommonOperationsTrait;
use InvalidArgumentException;
use Iterator;
use IteratorAggregate;
use Traversable;

/**
 * @internal
 */
final class DefaultingWhenEmptySequence implements Sequence
{
    use CommonOperationsTrait;

    /**
     * @var Sequence
     */
    private $previous;

    /**
     * @var callable () -> Sequence<T>
     */
    private $supplier;

    public function __construct(Sequence $previous, callable $supplier)
    {
        $this->previous = $previous;
        $this->supplier = $supplier;
    }

    public function getIterator(): Traversable
    {
        return new DeferredIterator(function (): Iterator {
            $previous = Iterations::fromTraversable($this->previous->getIterator());
            if ($previous->hasNext()) {
                return $previous;
            }
            return $this->unwrapIterator(call_user_func($this->supplier));
        });
    }

    private function unwrapIterator(Traversable $source): Iterator
    {
        if ($source instanceof Iterator) {
            return $source;
        } elseif ($source instanceof IteratorAggregate) {
            return $this->unwrapIterator($source->getIterator());
        }

        throw new InvalidArgumentException(sprintf(
            "Could not extract Iterator from unknown Traversable type: %s",
            get_class($source)
        ));
    }
}
