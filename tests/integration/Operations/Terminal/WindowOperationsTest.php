<?php

declare(strict_types=1);

namespace IntegrationTests\Operations\Terminal;

use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

use function BradynPoulsen\Sequences\emptySequence;
use function BradynPoulsen\Sequences\sequenceFrom;

final class WindowOperationsTest extends TestCase
{
    /**
     * @test
     */
    public function step_negative(): void
    {
        $this->expectException(InvalidArgumentException::class);
        emptySequence()->step(-1);
    }

    /**
     * @test
     */
    public function step_basicUsage(): void
    {
        $fibonnaci = sequenceFrom(function (): Generator {
            $prev = 1;
            $curr = 1;

            while (true) {
                list($prev, $curr) = [$curr, $prev + $curr];
                yield $prev;
            }
        });

        self::assertEquals([1, 8, 55], $fibonnaci->step(4)->take(3)->toArray());
    }
}
