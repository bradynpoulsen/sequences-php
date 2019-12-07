<?php

declare(strict_types=1);

namespace IntegrationTests\Operations\Terminal;

use BradynPoulsen\Sequences\Operations\Terminal\PredicateMatchingOperations;
use BradynPoulsen\Sequences\Sequence;
use PHPUnit\Framework\TestCase;
use function BradynPoulsen\Sequences\sequenceOf;

/**
 * Tests for {@see Sequence::all()}, {@see Sequence::any()}, and {@see Sequence::none()}.
 */
final class PredicateMatchingOperationsTest extends TestCase
{
    /**
     * @test
     */
    public function noMatches(): void
    {
        $sequence = sequenceOf(1, 2, 3);

        self::assertFalse($sequence->all('is_string'));
        self::assertFalse($sequence->any('is_string'));
        self::assertTrue($sequence->none('is_string'));
    }

    /**
     * @test
     */
    public function mixedMatches(): void
    {
        $sequence = sequenceOf(1, 2, 3);
        $isEven = function ($i) {
            return $i % 2 === 0;
        };

        self::assertFalse($sequence->all($isEven));
        self::assertTrue($sequence->any($isEven));
        self::assertFalse($sequence->none($isEven));
    }

    /**
     * @test
     */
    public function onlyMatches(): void
    {
        $sequence = sequenceOf(1, 2, 3);
        $isEven = function ($i) {
            return $i < 10;
        };

        self::assertTrue($sequence->all($isEven));
        self::assertTrue($sequence->any($isEven));
        self::assertFalse($sequence->none($isEven));
    }
}
