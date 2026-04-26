<?php

declare(strict_types=1);

namespace WaterJug;

use InvalidArgumentException;
use WaterJug\Search\BreadthFirstSearch;
use WaterJug\Search\SearchStrategy;

/**
 * Solves the Water Jug Problem using a pluggable search strategy (default: BFS).
 *
 * Given two vessels of capacity $a and $b litres, finds the minimum number
 * of steps to measure exactly $c litres in either vessel.
 *
 * Allowed operations (each counts as one step):
 *   - Fill a vessel to capacity
 *   - Empty a vessel completely
 *   - Pour from one vessel into the other until the source is empty
 *     or the destination is full
 */
class Solver
{
    public function __construct(
        private readonly SearchStrategy $strategy = new BreadthFirstSearch(),
    ) {}

    /**
     * Returns the minimum number of steps to obtain exactly $c litres
     * in either vessel, or -1 if it is impossible.
     * Throws InvalidArgumentException if any argument is not positive.
     */
    public function solve(int $a, int $b, int $c): int
    {
        if ($a <= 0 || $b <= 0 || $c <= 0) {
            throw new InvalidArgumentException(
                'Vessel capacities and target must be positive.'
            );
        }

        if (!$this->isSolvable($a, $b, $c)) {
            return -1;
        }

        return $this->strategy->search($a, $b, $c);
    }

    /**
     * A solution exists if and only if:
     *   - c is reachable (c <= max(a, b)), and
     *   - c is a multiple of gcd(a, b)
     * (by Bezout's identity, every achievable amount is a multiple of the greatest common divisor)
     */
    private function isSolvable(int $a, int $b, int $c): bool
    {
        if ($c > max($a, $b)) {
            return false;
        }

        return $c % $this->greatestCommonDivisor($a, $b) === 0;
    }

    private function greatestCommonDivisor(int $a, int $b): int
    {
        while ($b !== 0) {
            [$a, $b] = [$b, $a % $b];
        }

        return $a;
    }
}
