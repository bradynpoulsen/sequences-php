<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Iteration;

use Iterator;

/**
 * @internal
 */
interface Iteration extends Iterator
{
    /**
     * Indicates if there is another item available in this iteration.
     *
     * @return bool
     */
    public function hasNext(): bool;

    /**
     * Obtain the next item in this iteration.
     *
     * @return mixed
     */
    public function pluckNext();

    /**
     * @deprecated Iteration is a simplification of PHP Iterators. Use hasNext() or pluckNext() instead!
     */
    public function current();

    /**
     * @deprecated Iteration is a simplification of PHP Iterators. Use hasNext() or pluckNext() instead!
     */
    public function key();

    /**
     * @deprecated Iteration is a simplification of PHP Iterators. Use hasNext() or pluckNext() instead!
     */
    public function next();

    /**
     * Iterations NEVER rewind. No-op for backwards compatibility.
     *
     * @deprecated Iteration is a simplification of PHP Iterators. Use hasNext() or pluckNext() instead!
     */
    public function rewind();

    /**
     * @deprecated Iteration is a simplification of PHP Iterators. Use hasNext() or pluckNext() instead!
     */
    public function valid();
}
