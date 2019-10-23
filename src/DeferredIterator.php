<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences;

use Iterator;

/**
 * @internal
 */
final class DeferredIterator implements Iterator
{
    /**
     * @var Iterator|null
     */
    private $iterator = null;

    /**
     * @var callable|null
     */
    private $supplier;

    /**
     * @param callable $supplier () -> Iterator
     */
    public function __construct(callable $supplier)
    {
        $this->supplier = $supplier;
    }

    private function iterator(): Iterator
    {
        if ($this->iterator === null) {
            $this->iterator = call_user_func($this->supplier);
            $this->supplier = null;
        }

        return $this->iterator;
    }

    /**
     * @see Iterator::current()
     */
    public function current()
    {
        return $this->iterator()->current();
    }

    /**
     * @see Iterator::next()
     */
    public function next()
    {
        $this->iterator()->next();
    }

    /**
     * @see Iterator::key()
     */
    public function key()
    {
        return $this->iterator()->key();
    }

    /**
     * @see Iterator::valid()
     */
    public function valid()
    {
        return $this->iterator()->valid();
    }

    /**
     * @see Iterator::rewind()
     */
    public function rewind()
    {
        $this->iterator()->rewind();
    }
}
