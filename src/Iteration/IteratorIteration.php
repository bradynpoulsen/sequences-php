<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Iteration;

use Iterator;

/**
 * @internal
 */
final class IteratorIteration extends AbstractIteration
{
    /**
     * @var Iterator
     */
    private $source;

    /**
     * @var bool
     */
    private $started = false;

    public function __construct(Iterator $source)
    {
        $this->source = $source;
    }

    protected function computeNext(): void
    {
        if ($this->started) {
            $this->source->next();
        } else {
            $this->started = true;
            $this->source->rewind();
        }

        if ($this->source->valid()) {
            $this->setNext($this->source->current());
            return;
        }

        $this->source = null;
        $this->close();
    }

    public function close(): void
    {
        parent::close();
        $this->source = null;
    }
}
