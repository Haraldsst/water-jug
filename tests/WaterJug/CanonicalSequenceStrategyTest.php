<?php

declare(strict_types=1);

namespace WaterJug\Tests;

use WaterJug\Search\CanonicalSequenceStrategy;
use WaterJug\Solver;

class CanonicalSequenceStrategyTest extends AbstractSolverContractTest
{
    protected function makeSolver(): Solver
    {
        return new Solver(new CanonicalSequenceStrategy());
    }
}
