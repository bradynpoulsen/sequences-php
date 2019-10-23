<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Builder;

use BradynPoulsen\Sequences\Sequence;
use BradynPoulsen\Sequences\Traits\CommonOperationsTrait;
use EmptyIterator;
use Traversable;

/**
 * @internal
 */
final class EmptySequence implements Sequence
{
    use CommonOperationsTrait;

    public function getIterator(): Traversable
    {
        return new EmptyIterator();
    }
}
