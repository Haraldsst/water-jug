<?php

declare(strict_types=1);

namespace WaterJug\Tests;

use WaterJug\Solver;

class BreadthFirstSearchTest extends AbstractSolverContractTest
{
    protected function makeSolver(): Solver
    {
        return new Solver();
    }
}
