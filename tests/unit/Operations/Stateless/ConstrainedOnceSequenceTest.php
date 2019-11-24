<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Operations\Stateless;

use BradynPoulsen\Sequences\SequenceAlreadyIteratedException;
use PHPUnit\Framework\TestCase;
use function BradynPoulsen\Sequences\sequenceOf;

/**
 * Tests for {@see ConstrainedOnceSequence}.
 * @covers \BradynPoulsen\Sequences\Operations\Stateless\ConstrainedOnceSequence
 */
final class ConstrainedOnceSequenceTest extends TestCase
{
    /**
     * @test
     */
    public function allowsSingleUse(): void
    {
        $sequence = new ConstrainedOnceSequence(sequenceOf(1, 2, 3));
        self::assertSame([1, 2, 3], iterator_to_array($sequence));
    }

    /**
     * @test
     */
    public function failsOnSecondUse(): void
    {
        $this->expectException(SequenceAlreadyIteratedException::class);

        $sequence = new ConstrainedOnceSequence(sequenceOf(1, 2, 3));
        iterator_to_array($sequence);
        iterator_to_array($sequence);
    }
}
