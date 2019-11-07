<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Traits;

use BradynPoulsen\Sequences\SequenceOptions;
use BradynPoulsen\Sequences\Operations\Stateful\{
    FilteringSequence,
    WindowedSequence
};
use BradynPoulsen\Sequences\Sequence;

trait StatefulOperationsTrait
{
    /**
     * @see Sequence::chunked()
     */
    public function chunked(int $size, ?callable $transform = null): Sequence
    {
        return $this->windowed($size, $size, SequenceOptions::INCLUDE_PARTIAL_WINDOWS, $transform);
    }

    /**
     * @see Sequence::filter()
     */
    public function filter(callable $predicate): Sequence
    {
        return new FilteringSequence($this, $predicate);
    }

    /**
     * @see Sequence::filterIsInstance()
     */
    public function filterIsInstance(string $type): Sequence
    {
        return $this->filter(function ($element) use ($type): bool {
            return $element instanceof $type;
        });
    }

    /**
     * @see Sequence::filterNot()
     */
    public function filterNot(callable $predicate): Sequence
    {
        return new FilteringSequence($this, $predicate, FilteringSequence::SEND_WHEN_FALSE);
    }

    /**
     * @see Sequence::windowed()
     */
    public function windowed(
        int $size,
        int $step = 1,
        bool $partialWindows = SequenceOptions::NO_PARTIAL_WINDOWS,
        ?callable $transform = null
    ): Sequence {
        $sequence = new WindowedSequence(
            $this,
            $size,
            $step,
            $partialWindows
        );
        return $transform !== null ? $sequence->map($transform) : $sequence;
    }
}
