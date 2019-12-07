<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Operations\Stateful;

use BradynPoulsen\Sequences\DeferredIterator;
use BradynPoulsen\Sequences\Iteration\Iteration;
use BradynPoulsen\Sequences\Iteration\Iterations;
use BradynPoulsen\Sequences\Sequence;
use BradynPoulsen\Sequences\Traits\CommonOperationsTrait;
use Generator;
use InvalidArgumentException;
use Traversable;

use function count;

/**
 * @internal
 */
final class WindowedSequence implements Sequence
{
    use CommonOperationsTrait;

    /**
     * @var Sequence
     */
    private $previous;

    /**
     * @var int
     */
    private $size;

    /**
     * @var int
     */
    private $step;

    /**
     * @var bool
     */
    private $partialWindows;

    public function __construct(
        Sequence $previous,
        int $size,
        int $step,
        bool $partialWindows
    ) {
        if ($size === 0 || $step === 0) {
            $zeros = [];
            if ($size === 0) {
                array_push($zeros, 'size');
            }
            if ($step === 0) {
                array_push($zeros, 'step');
            }
            throw new InvalidArgumentException(sprintf(
                '%s must be greater than zero!',
                implode(' and ', $zeros)
            ));
        }

        $this->previous = $previous;
        $this->size = $size;
        $this->step = $step;
        $this->partialWindows = $partialWindows;
    }

    public function getIterator(): Traversable
    {
        return new DeferredIterator(function (): Generator {
            $gap = $this->step - $this->size;
            $previous = Iterations::fromTraversable($this->previous->getIterator());
            if ($gap >= 0) {
                return $this->isolatedWindows($previous, $gap);
            } else {
                return $this->overlappingWindows($previous);
            }
        });
    }

    /**
     * Creates buffered windows that do not overlap.
     *
     * @param Iteration $previous
     * @param int $gap
     *
     * @return Generator
     */
    private function isolatedWindows(Iteration $previous, int $gap): Generator
    {
        $buffer = [];
        $skip = 0;
        while ($previous->hasNext()) {
            $element = $previous->pluckNext();
            if ($skip > 0) {
                $skip--;
                continue;
            }
            array_push($buffer, $element);
            if (count($buffer) === $this->size) {
                yield $buffer;
                $buffer = [];
                $skip = $gap;
            }
        }
        if (count($buffer) > 0 && $this->partialWindows) {
            yield $buffer;
        }
    }

    /**
     * Creates buffered windows that overlap.
     *
     * @param Iteration $previous
     *
     * @return Generator
     */
    private function overlappingWindows(Iteration $previous): Generator
    {
        $buffer = [];
        while ($previous->hasNext()) {
            array_push($buffer, $previous->pluckNext());
            if (count($buffer) === $this->size) {
                yield $buffer;
                $this->arrayDrop($buffer, $this->step);
            }
        }
        if ($this->partialWindows) {
            while (count($buffer) > $this->step) {
                yield $buffer;
                $this->arrayDrop($buffer, $this->step);
            }
            if (count($buffer) > 0) {
                yield $buffer;
            }
        }
    }

    private function arrayDrop(array &$source, int $count): void
    {
        for ($dropped = 0; $dropped < $count; $dropped++) {
            array_shift($source);
        }
    }
}
