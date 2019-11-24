<?php

declare(strict_types=1);

namespace BradynPoulsen\Sequences\Operations\Stateless;

use BradynPoulsen\Sequences\Sequence;
use PHPUnit\Framework\TestCase;
use Traversable;
use function BradynPoulsen\Sequences\emptySequence;
use function BradynPoulsen\Sequences\sequenceOf;

/**
 * Tests for {@see DefaultingWhenEmptySequence}.
 * @covers \BradynPoulsen\Sequences\Operations\Stateless\DefaultingWhenEmptySequence
 */
final class DefaultingWhenEmptySequenceTest extends TestCase
{
    /**
     * @test
     * @dataProvider provideScenarios
     */
    public function defaultingScenarios(Sequence $source, Traversable $default, array $expected): void
    {
        $sequence = new DefaultingWhenEmptySequence($source, function () use ($default) {
            return $default;
        });
        self::assertSame($expected, iterator_to_array($sequence));
    }

    /**
     * Data provider for {@see }.
     *
     * @return array [source: Sequence, default: Traversable, expectedElements: array]
     */
    public function provideScenarios(): array
    {
        return [
            [sequenceOf(1, 2, 3), sequenceOf(4, 5, 6), [1, 2, 3]],
            [emptySequence(), sequenceOf(4, 5, 6), [4, 5, 6]],
        ];
    }
}
