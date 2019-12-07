<?php

declare(strict_types=1);

namespace IntegrationTests;

use ArrayAccess;
use BradynPoulsen\Sequences\Compose;
use PHPUnit\Framework\TestCase;
use function BradynPoulsen\Sequences\sequenceOf;

final class SelectorFnTest extends TestCase
{
    /**
     * @test
     */
    public function selectIndex_array(): void
    {
        self::assertEquals(
            ['first', 'second', 'third'],
            sequenceOf(
                ['int' => 1, 'text' => 'first'],
                ['int' => 2, 'text' => 'second'],
                ['int' => 3, 'text' => 'third']
            )->toArray(Compose::selectIndex('text'))
        );
    }

    /**
     * @test
     */
    public function selectIndex_ArrayAccess(): void
    {
        self::assertEquals(
            [90, 180, 270],
            sequenceOf(
                $this->createMultipliedLengthArrayAccess(10),
                $this->createMultipliedLengthArrayAccess(20),
                $this->createMultipliedLengthArrayAccess(30)
            )->toArray(Compose::selectIndex('123456789'))
        );
    }

    /**
     * @test
     */
    public function selectProperty_fields(): void
    {
        self::assertEquals(
            ['first', 'second', 'third'],
            sequenceOf(
                (object)['int' => 1, 'text' => 'first'],
                (object)['int' => 2, 'text' => 'second'],
                (object)['int' => 3, 'text' => 'third']
            )->toArray(Compose::selectProperty('text'))
        );
    }

    private function createMultipliedLengthArrayAccess(int $multiplier): object
    {
        return new class($multiplier) implements ArrayAccess {
            /**
             * @var int
             */
            private $multiplier;

            public function __construct(int $multiplier)
            {
                $this->multiplier = $multiplier;
            }

            public function offsetGet($offset)
            {
                return strlen($offset) * $this->multiplier;
            }

            public function offsetExists($offset)
            {
            }

            public function offsetSet($offset, $value)
            {
            }

            public function offsetUnset($offset)
            {
            }
        };
    }


}
