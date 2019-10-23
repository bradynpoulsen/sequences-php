<?php
declare(strict_types=1);

namespace IntegrationTests;

use ArrayIterator;
use BradynPoulsen\Sequences\SequenceAlreadyIteratedException;
use Generator;
use IteratorAggregate;
use PHPUnit\Framework\TestCase;

use function BradynPoulsen\Sequences\sequenceFrom;
use function BradynPoulsen\Sequences\sequenceOf;

final class BuildersTest extends TestCase
{
    /**
     * @test
     * @covers ::sequenceFrom()
     * @covers \BradynPoulsen\Sequences\Builder\GeneratingSequence
     */
    public function sequenceFrom_with_generator_function(): void
    {
        $sequence = sequenceFrom(function (): Generator {
            yield 1;
            yield 2;
            yield 3;
        });
        self::assertEquals([1, 2, 3], iterator_to_array($sequence));
        self::assertEquals([1, 2, 3], iterator_to_array($sequence));
    }

    /**
     * @test
     * @covers ::sequenceFrom()
     * @covers \BradynPoulsen\Sequences\Builder\TraversableSequence
     */
    public function sequenceFrom_iterator(): void
    {
        $sequence = sequenceFrom(new ArrayIterator([1, 2, 3]));
        self::assertEquals([1, 2, 3], iterator_to_array($sequence));
    }

    /**
     * @test
     * @covers ::sequenceFrom()
     * @covers \BradynPoulsen\Sequences\Builder\TraversableSequence
     */
    public function sequenceFrom_iterator_fails_on_second_iteration(): void
    {
        $sequence = sequenceFrom(new ArrayIterator([1, 2, 3]));

        $this->expectException(SequenceAlreadyIteratedException::class);

        iterator_to_array($sequence);
        iterator_to_array($sequence);
    }

    /**
     * @test
     * @covers ::sequenceFrom()
     * @covers \BradynPoulsen\Sequences\Builder\GeneratingSequence
     */
    public function sequenceFrom_iteratorAggregate(): void
    {
        $sequence = sequenceFrom(new class implements IteratorAggregate
        {
            public function getIterator(): Generator
            {
                yield 1;
                yield 2;
                yield 3;
                yield 4;
            }
        });
        self::assertEquals([1, 2, 3, 4], iterator_to_array($sequence));
        self::assertEquals([1, 2, 3, 4], iterator_to_array($sequence));
    }

    /**
     * @test
     * @covers ::sequenceOf()
     * @covers \BradynPoulsen\Sequences\Builder\StaticSequence
     */
    public function sequenceOf_static_set_of_numbers(): void
    {
        $sequence = sequenceOf(1, 2, 3, 4);
        self::assertEquals([1, 2, 3, 4], iterator_to_array($sequence));
        self::assertEquals([1, 2, 3, 4], iterator_to_array($sequence));
    }
}
