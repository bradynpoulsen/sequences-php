<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences;

use BradynPoulsen\Sequences\Builder\EmptySequence;
use BradynPoulsen\Sequences\Builder\GeneratingSequence;
use BradynPoulsen\Sequences\Builder\StaticSequence;
use BradynPoulsen\Sequences\Builder\TraversableSequence;
use Closure;
use InvalidArgumentException;
use Iterator;
use IteratorAggregate;

/**
 * Creates an empty sequence.
 *
 * @return Sequence
 */
function emptySequence(): Sequence
{
    return new EmptySequence();
}

/**
 * Creates a sequence from the provided {@see Iterator}, {@see IteratorAggregate}, or {@see callable} () -> Traversable.
 *
 * @param IteratorAggregate|Iterator|callable $source
 *
 * @return Sequence
 */
function sequenceFrom($source): Sequence
{
    if ($source instanceof Sequence) {
        return $source;
    } elseif ($source instanceof Iterator) {
        return new TraversableSequence($source);
    } elseif ($source instanceof IteratorAggregate) {
        return new GeneratingSequence(Closure::fromCallable([$source, 'getIterator']));
    } elseif (is_callable($source)) {
        return new GeneratingSequence($source);
    }

    $type = is_object($source) ? get_class($source) : gettype($source);
    throw new InvalidArgumentException("Cannot create Sequence from provided ${type}");
}

/**
 * Creates a sequence from the provided $elements.
 *
 * @param mixed ...$elements
 *
 * @return Sequence
 */
function sequenceOf(...$elements): Sequence
{
    return new StaticSequence($elements);
}
