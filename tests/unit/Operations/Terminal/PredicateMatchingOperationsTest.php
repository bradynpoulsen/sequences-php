<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Operations\Terminal;

use PHPUnit\Framework\TestCase;

use function BradynPoulsen\Sequences\emptySequence;
use function BradynPoulsen\Sequences\sequenceOf;

/**
 * Tests for {@see PredicateMatchingOperations}.
 * @covers \BradynPoulsen\Sequences\Operations\Terminal\PredicateMatchingOperations
 */
final class PredicateMatchingOperationsTest extends TestCase
{
    /**
     * @test for {@see PredicateMatchingOperations::all()}
     */
    public function all_allMatches(): void
    {
        $sequence = sequenceOf(1, 2, 3);
        self::assertTrue(PredicateMatchingOperations::all($sequence, function ($e) {
            return $e < 10;
        }));
    }

    /**
     * @test for {@see PredicateMatchingOperations::all()}
     */
    public function all_mixedMatches(): void
    {
        $sequence = sequenceOf(1, 2, 3);
        self::assertFalse(PredicateMatchingOperations::all($sequence, function ($e) {
            return $e % 2 == 0;
        }));
    }

    /**
     * @test for {@see PredicateMatchingOperations::all()}
     */
    public function all_noMatches(): void
    {
        $sequence = sequenceOf(1, 2, 3);
        self::assertFalse(PredicateMatchingOperations::all($sequence, 'is_string'));
    }

    /**
     * @test for {@see PredicateMatchingOperations::any()}
     */
    public function any_noPredicate(): void
    {
        $sequenceEmpty = emptySequence();
        self::assertFalse(PredicateMatchingOperations::any($sequenceEmpty));

        $sequenceNonEmpty = sequenceOf(1, 2, 3);
        self::assertTrue(PredicateMatchingOperations::any($sequenceNonEmpty));
    }

    /**
     * @test for {@see PredicateMatchingOperations::any()}
     */
    public function any_allMatches(): void
    {
        $sequence = sequenceOf(1, 2, 3);
        self::assertTrue(PredicateMatchingOperations::any($sequence, function ($e) {
            return $e < 10;
        }));
    }

    /**
     * @test for {@see PredicateMatchingOperations::any()}
     */
    public function any_mixedMatches(): void
    {
        $sequence = sequenceOf(1, 2, 3);
        self::assertTrue(PredicateMatchingOperations::any($sequence, function ($e) {
            return $e % 2 == 0;
        }));
    }

    /**
     * @test for {@see PredicateMatchingOperations::any()}
     */
    public function any_noMatches(): void
    {
        $sequence = sequenceOf(1, 2, 3);
        self::assertFalse(PredicateMatchingOperations::any($sequence, 'is_string'));
    }

    /**
     * @test for {@see PredicateMatchingOperations::none()}
     */
    public function none_noPredicate(): void
    {
        $sequenceEmpty = emptySequence();
        self::assertTrue(PredicateMatchingOperations::none($sequenceEmpty));

        $sequenceNonEmpty = sequenceOf(1, 2, 3);
        self::assertFalse(PredicateMatchingOperations::none($sequenceNonEmpty));
    }

    /**
     * @test for {@see PredicateMatchingOperations::none()}
     */
    public function none_allMatches(): void
    {
        $sequence = sequenceOf(1, 2, 3);
        self::assertFalse(PredicateMatchingOperations::none($sequence, function ($e) {
            return $e < 10;
        }));
    }

    /**
     * @test for {@see PredicateMatchingOperations::none()}
     */
    public function none_mixedMatches(): void
    {
        $sequence = sequenceOf(1, 2, 3);
        self::assertFalse(PredicateMatchingOperations::none($sequence, function ($e) {
            return $e % 2 == 0;
        }));
    }

    /**
     * @test for {@see PredicateMatchingOperations::none()}
     */
    public function none_noMatches(): void
    {
        $sequence = sequenceOf(1, 2, 3);
        self::assertTrue(PredicateMatchingOperations::none($sequence, 'is_string'));
    }
}
