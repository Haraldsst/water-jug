<?php

declare(strict_types=1);

namespace WaterJug\Search;

final class JugState
{
    public function __construct(
        public readonly int $a,
        public readonly int $b,
    ) {}

    public function contains(int $target): bool
    {
        return $this->a === $target || $this->b === $target;
    }

    public function key(): string
    {
        return "{$this->a},{$this->b}";
    }
}
