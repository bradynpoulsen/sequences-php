<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Iteration;

/**
 * @internal
 */
interface IterationBuilder
{
    public function close(): void;
    public function getIndex(): int;
    public function setNext($element): void;
    public function skipping(): void;
}
