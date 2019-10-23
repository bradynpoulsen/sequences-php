<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Iteration;

/**
 * @internal
 */
final class ComputingIteration extends AbstractIteration
{
    /**
     * @var callable
     */
    private $builder;

    /**
     * @var Iteration|null
     */
    private $previous;

    public function __construct(callable $builder, ?Iteration $previous = null)
    {
        $this->builder = $builder;
        $this->previous = $previous;
    }

    protected function computeNext(): void
    {
        if ($this->previous instanceof Iteration) {
            call_user_func($this->builder, $this->previous, $this);
        } else {
            call_user_func($this->builder, $this);
        }
    }

    public function close(): void
    {
        parent::close();
        $this->previous = null;
        $this->builder = null;
    }
}
