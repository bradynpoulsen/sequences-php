<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Traits;

use BradynPoulsen\Sequences\Operations\Stateful\FilteringSequence;
use BradynPoulsen\Sequences\Sequence;

trait StatefulOperationsTrait
{
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
        return new FilteringSequence($this, $predicate, false);
    }
}
