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

    private static $instance = null;

    public static function get(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
    }

    public function getIterator(): Traversable
    {
        return new EmptyIterator();
    }
}
