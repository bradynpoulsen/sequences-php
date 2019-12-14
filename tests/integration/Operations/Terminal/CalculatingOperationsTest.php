<?php

declare(strict_types=1);

namespace IntegrationTests\Operations\Terminal;

use BradynPoulsen\Sequences\Compose;
use InvalidArgumentException;
use LengthException;
use PHPUnit\Framework\TestCase;

use function BradynPoulsen\Sequences\emptySequence;
use function BradynPoulsen\Sequences\sequenceOf;

final class CalculatingOperationsTest extends TestCase
{
    /**
     * @test
     */
    public function average(): void
    {
        self::assertEquals(2.5, sequenceOf(1, 2, 3, 4)->average());
        self::assertEquals(2.85, sequenceOf(1.2, 2.3, 3.4, 4.5)->average());
    }

    /**
     * @test
     */
    public function averageBy(): void
    {
        $source = sequenceOf([1, 1.2], [2, 2.3], [3, 3.4], [4, 4.5]);
        self::assertEquals(2.5, $source->averageBy(Compose::selectIndex(0)));
        self::assertEquals(2.85, $source->averageBy(Compose::selectIndex(1)));
    }

    /**
     * @test
     */
    public function fold(): void
    {
        $source = sequenceOf([1, 1.2], [2, 2.3], [3, 3.4], [4, 4.5]);
        self::assertEquals([
            1.2,
            4.6,
            10.2,
            18
        ], $source->fold([], function (array $acc, array $element): array {
            return array_merge($acc, [$element[0] * $element[1]]);
        }));
    }

    /**
     * @test
     */
    public function reduce(): void
    {
        self::assertEquals(
            [1, 1.2, 2, 2.3, 3, 3.4, 4, 4.5],
            sequenceOf([1, 1.2], [2, 2.3], [3, 3.4], [4, 4.5])
                ->reduce(function (array $acc, array $element): array {
                    return array_merge($acc, $element);
                })
        );
    }

    /**
     * @test
     */
    public function reduce_empty(): void
    {
        $this->expectException(LengthException::class);
        emptySequence()->reduce(function ($acc) {
            return $acc;
        });
    }

    /**
     * @test
     */
    public function sum(): void
    {
        self::assertEquals(10, sequenceOf(1, 2, 3, 4)->sum());
        self::assertEquals(11.4, sequenceOf(1.2, 2.3, 3.4, 4.5)->sum());
    }

    /**
     * @test
     */
    public function sum_validateNumber(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Element must be an integer or float");
        sequenceOf("foo")->sum();
    }

    /**
     * @test
     */
    public function sumBy(): void
    {
        $source = sequenceOf([1, 1.2], [2, 2.3], [3, 3.4], [4, 4.5]);
        self::assertEquals(10, $source->sumBy(Compose::selectIndex(0)));
        self::assertEquals(11.4, $source->sumBy(Compose::selectIndex(1)));
    }
}
