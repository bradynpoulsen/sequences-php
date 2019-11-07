<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Iteration;

use BadMethodCallException;
use OutOfBoundsException;
use UnexpectedValueException;

/**
 * @internal
 */
abstract class AbstractIteration implements Iteration, IterationBuilder
{
    private const STATE_NOT_READY = 0;
    private const STATE_READY = 1;
    private const STATE_CLOSED = 2;
    private const STATE_FAILED = 3;

    private $state = self::STATE_NOT_READY;
    private $nextValue = null;
    private $nextIndex = -1;

    abstract protected function computeNext(): void;

    public function hasNext(): bool
    {
        switch ($this->state) {
            case self::STATE_FAILED:
                throw new UnexpectedValueException("Iteration failed!");
            case self::STATE_CLOSED:
                return false;
            case self::STATE_READY:
                return true;
            default:
                return $this->tryComputeNext();
        }
    }

    public function pluckNext()
    {
        if (!$this->hasNext()) {
            throw new OutOfBoundsException("No more elements available!");
        }
        $this->state = self::STATE_NOT_READY;
        $value = $this->nextValue;
        $this->nextValue = null;
        return $value;
    }

    private function tryComputeNext(): bool
    {
        $this->state = self::STATE_FAILED;
        $this->nextIndex++;
        $this->computeNext();
        return $this->state === self::STATE_READY;
    }

    public function close(): void
    {
        $this->state = self::STATE_CLOSED;
    }

    public function getIndex(): int
    {
        return $this->nextIndex;
    }

    public function setNext($element): void
    {
        $this->nextValue = $element;
        $this->state = self::STATE_READY;
    }

    public function skipping(): void
    {
        $this->nextIndex++;
    }

    public function rewind()
    {
        if ($this->nextIndex > 0 || $this->nextIndex === 0 && $this->state !== self::STATE_READY) {
            throw new BadMethodCallException("This iterator cannot be rewound");
        }
    }

    public function next()
    {
        $this->pluckNext();
    }

    public function valid(): bool
    {
        return $this->hasNext();
    }

    public function current()
    {
        assert($this->hasNext());
        return $this->nextValue;
    }

    public function key(): int
    {
        assert($this->hasNext());
        return $this->nextIndex;
    }
}
