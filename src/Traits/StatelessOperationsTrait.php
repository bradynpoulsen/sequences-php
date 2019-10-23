<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Traits;

use BradynPoulsen\Sequences\Operations\Stateless\ConstrainedOnceSequence;
use BradynPoulsen\Sequences\Sequence;

trait StatelessOperationsTrait
{
    /**
     * {@see Sequence::constrainOnce()}
     */
    public function constrainOnce(): Sequence
    {
        assert($this instanceof Sequence);
        if ($this instanceof ConstrainedOnceSequence) {
            return $this;
        }
        return new ConstrainedOnceSequence($this);
    }
}
