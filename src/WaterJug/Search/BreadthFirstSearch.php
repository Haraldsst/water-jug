<?php

declare(strict_types=1);

namespace WaterJug\Search;

use SplQueue;

final class BreadthFirstSearch implements SearchStrategy
{
    public function search(int $capA, int $capB, int $target): int
    {
        $initial = new JugState(0, 0);
        $visited = [];
        /** @var SplQueue<array{JugState, int}> $queue */
        $queue   = new SplQueue();

        $queue->enqueue([$initial, 0]);
        $visited[$initial->key()] = true;

        while (!$queue->isEmpty()) {
            [$state, $steps] = $queue->dequeue();

            foreach ($this->nextStates($state, $capA, $capB) as $next) {
                if ($next->contains($target)) {
                    return $steps + 1;
                }

                $key = $next->key();
                if (!isset($visited[$key])) {
                    $visited[$key] = true;
                    $queue->enqueue([$next, $steps + 1]);
                }
            }
        }

        return -1;
    }

    /** @return list<JugState> */
    private function nextStates(JugState $state, int $capA, int $capB): array
    {
        $pour = static fn(int $from, int $to, int $toCap): array => [
            max(0, $from - ($toCap - $to)),
            min($toCap, $to + $from),
        ];

        [$pourAtoB_newA, $pourAtoB_newB] = $pour($state->a, $state->b, $capB);
        [$pourBtoA_newB, $pourBtoA_newA] = $pour($state->b, $state->a, $capA);

        return [
            new JugState($capA, $state->b),                    // Fill A
            new JugState($state->a, $capB),                    // Fill B
            new JugState(0, $state->b),                        // Empty A
            new JugState($state->a, 0),                        // Empty B
            new JugState($pourAtoB_newA, $pourAtoB_newB),      // Pour A → B
            new JugState($pourBtoA_newA, $pourBtoA_newB),      // Pour B → A
        ];
    }
}
