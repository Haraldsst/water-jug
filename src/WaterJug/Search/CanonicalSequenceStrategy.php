<?php

declare(strict_types=1);

namespace WaterJug\Search;

/**
 * Solves the Water Jug Problem by simulating the two canonical pouring sequences.
 *
 * Instead of exploring the state space, this strategy simulates the two
 * canonical pouring sequences algebraically and picks the shorter one:
 *   - Fill A repeatedly, pouring into B, emptying B when full
 *   - Fill B repeatedly, pouring into A, emptying A when full
 *
 * Every BFS-optimal path either starts by filling A or by filling B.
 * Within each family the canonical sequence is optimal — it never
 * revisits a state. Therefore min(stepsFillingA, stepsFillingB)
 * always equals the true BFS minimum.
 *
 * Complexity: O(max(a, b)) time, O(1) space — no queue or visited set.
 */
final class CanonicalSequenceStrategy implements SearchStrategy
{
    public function search(int $capA, int $capB, int $target): int
    {
        $stepsFillingA = $this->stepsFillingFirst($capA, $capB, $target);
        $stepsFillingB = $this->stepsFillingFirst($capB, $capA, $target);

        return match (true) {
            $stepsFillingA === -1 && $stepsFillingB === -1 => -1,
            $stepsFillingA === -1 => $stepsFillingB,
            $stepsFillingB === -1 => $stepsFillingA,
            default               => min($stepsFillingA, $stepsFillingB),
        };
    }

    /**
     * Simulates the sequence where we repeatedly fill vessel A (capacity $fillCap),
     * pour into vessel B (capacity $receiveCap), and empty B when full.
     *
     * Steps are counted as: each fill of A (+1), each pour (+1), each empty of B (+1).
     *
     * Termination: the receive vessel has at most $receiveCap distinct states
     * (0 … $receiveCap − 1), so by the pigeonhole principle the sequence repeats
     * within $receiveCap fills. If $target has not appeared by then, it is
     * unreachable via this sequence.
     *
     * Returns -1 if $target is never hit.
     */
    private function stepsFillingFirst(int $fillCap, int $receiveCap, int $target): int
    {
        $inFill    = 0;
        $inReceive = 0;
        $steps     = 0;

        for ($fills = 0; $fills < $receiveCap; $fills++) {
            $inFill = $fillCap;
            $steps++;

            if ($inFill === $target || $inReceive === $target) {
                return $steps;
            }

            while ($inFill > 0 && $inReceive < $receiveCap) {
                $pour      = min($inFill, $receiveCap - $inReceive);
                $inFill   -= $pour;
                $inReceive += $pour;
                $steps++;

                if ($inFill === $target || $inReceive === $target) {
                    return $steps;
                }

                if ($inReceive === $receiveCap) {
                    $inReceive = 0;
                    $steps++;

                    if ($inFill === $target) {
                        return $steps;
                    }
                }
            }
        }

        return -1;
    }
}
