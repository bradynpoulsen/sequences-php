<?php
declare(strict_types=1);

namespace BradynPoulsen\Sequences;

use Throwable;
use UnexpectedValueException;

/**
 * Indicates a sequence that is constrained to be iterated only once has been iterated over a second time.
 */
final class SequenceAlreadyIteratedException extends UnexpectedValueException
{
    /**
     * @var Sequence
     */
    private $sequence;

    public function __construct(Sequence $source, Throwable $previous = null)
    {
        parent::__construct("Cannot iterate over constrained sequence multiple times", 0, $previous);
        $this->sequence = $source;
    }

    /**
     * Getter for {@see SequenceAlreadyIteratedException::$sequence}.
     *
     * @return Sequence
     */
    public function getSequence(): Sequence
    {
        return $this->sequence;
    }
}