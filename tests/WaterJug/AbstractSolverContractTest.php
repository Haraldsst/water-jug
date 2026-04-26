<?php

declare(strict_types=1);

namespace WaterJug\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use WaterJug\Solver;

abstract class AbstractSolverContractTest extends TestCase
{
    private Solver $solver;

    abstract protected function makeSolver(): Solver;

    protected function setUp(): void
    {
        $this->solver = $this->makeSolver();
    }

    // --- Provided examples ---

    public function testProvidedExampleOne(): void
    {
        // Vessels: 5L and 2L, target: 3L
        // Fill 5L (1), pour into 2L (2) → 3L remains in the 5L vessel
        $this->assertSame(2, $this->solver->solve(5, 2, 3));
    }

    public function testProvidedExampleTwo(): void
    {
        // Vessels: 2L and 3L, target: 4L → impossible (max is 3L)
        $this->assertSame(-1, $this->solver->solve(2, 3, 4));
    }

    // --- Edge cases ---

    public function testTargetIsZero(): void
    {
        $this->assertSame(0, $this->solver->solve(5, 3, 0));
    }

    public function testTargetEqualsVesselA(): void
    {
        // One step: just fill vessel A
        $this->assertSame(1, $this->solver->solve(4, 3, 4));
    }

    public function testTargetEqualsVesselB(): void
    {
        // One step: just fill vessel B
        $this->assertSame(1, $this->solver->solve(4, 3, 3));
    }

    public function testImpossibleBecauseNotMultipleOfGcd(): void
    {
        // gcd(4, 6) = 2, so 3 is not achievable
        $this->assertSame(-1, $this->solver->solve(4, 6, 3));
    }

    public function testImpossibleBecauseTargetExceedsCapacity(): void
    {
        $this->assertSame(-1, $this->solver->solve(3, 2, 5));
    }

    public function testEqualVessels(): void
    {
        // Both vessels hold 5L, target 5L → fill either one: 1 step
        $this->assertSame(1, $this->solver->solve(5, 5, 5));
    }

    public function testEqualVesselsImpossible(): void
    {
        // gcd(5, 5) = 5, so 3 is not achievable
        $this->assertSame(-1, $this->solver->solve(5, 5, 3));
    }

    // --- Input validation ---

    public function testNegativeCapacityThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->solver->solve(-1, 5, 3);
    }

    public function testZeroCapacityThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->solver->solve(0, 5, 3);
    }

    public function testNegativeTargetThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->solver->solve(5, 3, -1);
    }

    public function testZeroCapacityBThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->solver->solve(5, 0, 3);
    }

    // --- Known results ---

    #[DataProvider('knownResultsProvider')]
    public function testKnownResults(int $a, int $b, int $c, int $expected): void
    {
        $this->assertSame($expected, $this->solver->solve($a, $b, $c));
    }

    /** @return array<string, array{int, int, int, int}> */
    public static function knownResultsProvider(): array
    {
        return [
            // fill-B sequence is shorter (fill-A: 8, fill-B: 6)
            '3L and 5L → 4L'                              => [3, 5, 4, 6],
            // fill-B sequence is shorter (fill-A: 4, fill-B: 2)
            '2L and 3L → 1L'                              => [2, 3, 1, 2],
            // fill-A sequence is shorter (fill-A: 6, fill-B: 8) — same vessels, swapped
            '5L and 3L → 4L (fill-A shorter)'             => [5, 3, 4, 6],
            // fill-A sequence is shorter (fill-A: 4, fill-B: 8)
            '3L and 5L → 1L (fill-A shorter)'             => [3, 5, 1, 4],
            // fill-B sequence is shorter (fill-A: 8, fill-B: 4)
            '5L and 3L → 1L (fill-B shorter)'             => [5, 3, 1, 4],
            // gcd(6, 10) = 2; fill-B finishes in 2 steps, fill-A in 10
            'gcd > 1, large asymmetry: 6L and 10L → 4L'  => [6, 10, 4, 2],
            // unit vessel guarantees gcd = 1 (fill-B: 2 steps)
            'unit vessel: any amount reachable (gcd=1)'   => [1, 7, 6, 2],
            'large coprime vessels terminate'             => [7, 11, 5, 12],
        ];
    }
}
