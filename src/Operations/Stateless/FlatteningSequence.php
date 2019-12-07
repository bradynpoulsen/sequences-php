<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Operations\Stateless;

use BradynPoulsen\Sequences\Operations\DeferredIterator;
use BradynPoulsen\Sequences\Sequence;
use BradynPoulsen\Sequences\Traits\CommonOperationsTrait;
use Generator;
use InvalidArgumentException;
use Traversable;

/**
 * @internal
 */
final class FlatteningSequence implements Sequence
{
    use CommonOperationsTrait;
    use CommonOperationsTrait {
        map as commonMap;
    }

    /**
     * @var Sequence
     */
    private $previous;

    /**
     * @var callable|null (T $element[, int $index]) -> iterable<R>
     */
    private $transform;

    public function __construct(Sequence $previous, ?callable $transform = null)
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
                $element = ($this->transform !== null)
                    ? call_user_func($this->transform, $element, $index++)
                    : $element;
                if (!is_iterable($element)) {
                    throw new InvalidArgumentException(sprintf(
                        "Expected a iterable but got %s",
                        is_object($element) ? get_class($element) : gettype($element)
                    ));
                }
                yield from $element;
            }
        });
    }

    public function map(callable $transform): Sequence
    {
        if ($this->transform === null) {
            $this->transform = $transform;
            return $this;
        }

        return $this->commonMap($transform);
    }
}
