<?php

declare(strict_types=1);

namespace WaterJug\Search;

interface SearchStrategy
{
    /**
     * Finds the minimum number of steps from the empty state (0, 0) to any state
     * containing $target, given vessel capacities $capA and $capB.
     * Returns -1 if unreachable.
     */
    public function search(int $capA, int $capB, int $target): int;
}
