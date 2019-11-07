<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Iteration;

use IteratorAggregate;
use Traversable;

/**
 * @internal
 */
final class Iterations
{
    private function __construct()
    {
    }

    /**
     * Calculate an iteration of value using an {@see IterationBuilder} consumer.
     *
     * @param callable $builder (IterationBuilder) -> void
     *
     * @return Iteration
     */
    public static function build(callable $builder): Iteration
    {
        return new ComputingIteration($builder);
    }

    /**
     * Calculate an iteration of values based on a linked {@see Iteration} of values.
     *
     * @param Traversable $previous
     * @param callable $builder (Iteration $previous, IterationBuilder $builder) -> void
     *
     * @return Iteration
     */
    public static function buildLinked(Traversable $previous, callable $builder): Iteration
    {
        return new ComputingIteration($builder, self::fromTraversable($previous));
    }

    /**
     * Adapt the given $source traversable into an {@see Iteration}.
     *
     * @param Traversable $source
     *
     * @return Iteration
     */
    public static function fromTraversable(Traversable $source): Iteration
    {
        if ($source instanceof IteratorAggregate) {
            return self::fromTraversable($source->getIterator());
        } elseif ($source instanceof Iteration) {
            return $source;
        }

        return new IteratorIteration($source);
    }
}
