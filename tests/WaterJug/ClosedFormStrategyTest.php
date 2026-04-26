<?php

declare(strict_types=1);

namespace WaterJug\Tests;

use WaterJug\Search\ClosedFormStrategy;
use WaterJug\Solver;

class ClosedFormStrategyTest extends AbstractSolverContractTest
{
    protected function makeSolver(): Solver
    {
        return new Solver(new ClosedFormStrategy());
    }
}
