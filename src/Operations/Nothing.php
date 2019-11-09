<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Operations;

/**
 * Singleton value to represent a value that MUST NOT be returned by any sequence operation.
 *
 * @internal
 */
final class Nothing
{
    /**
     * @var Nothing
     */
    private static $instance;

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
}
