<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences;

/**
 * Public-facing sequence options to enhance readability.
 */
final class SequenceOptions
{
    /**
     * @see Sequence::windowed()
     * @see SequenceOptions::NO_PARTIAL_WINDOWS
     */
    public const INCLUDE_PARTIAL_WINDOWS = true;

    /**
     * @see Sequence::windowed()
     * @see SequenceOptions::INCLUDE_PARTIAL_WINDOWS
     */
    public const NO_PARTIAL_WINDOWS = false;

    private function __construct()
    {
    }
}
