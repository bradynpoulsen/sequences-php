<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Traits;

use BradynPoulsen\Sequences\SequenceOptions;
use BradynPoulsen\Sequences\Operations\Stateful\{
    FilteringSequence
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
     * @see Sequence::filterNot()
     */
    public function filterNot(callable $predicate): Sequence
    {
        return new FilteringSequence($this, $predicate, FilteringSequence::SEND_WHEN_FALSE);
    }
}
