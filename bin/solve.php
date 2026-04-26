#!/usr/bin/env php
<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use WaterJug\Solver;

/**
 * Reads a non-negative integer from stdin, skipping blank lines.
 * Returns null on EOF, exits with an error message on non-numeric input.
 */
function readInt(): ?int
{
    while (($line = fgets(STDIN)) !== false) {
        $trimmed = trim($line);
        if ($trimmed === '') {
            continue;
        }
        if (!ctype_digit($trimmed)) {
            fwrite(STDERR, "Error: expected a non-negative integer, got \"{$trimmed}\"\n");
            exit(1);
        }
        return (int) $trimmed;
    }

    return null;
}

$solver = new Solver();

$t = readInt();
if ($t === null) {
    fwrite(STDERR, "Error: missing input\n");
    exit(1);
}
if ($t < 1 || $t > 100) {
    fwrite(STDERR, "Error: t must be between 1 and 100, got {$t}\n");
    exit(1);
}

for ($i = 0; $i < $t; $i++) {
    $a = readInt();
    $b = readInt();
    $c = readInt();

    if ($a === null || $b === null || $c === null) {
        fwrite(STDERR, "Error: incomplete input for test case " . ($i + 1) . "\n");
        exit(1);
    }

    foreach (['a' => $a, 'b' => $b, 'c' => $c] as $name => $val) {
        if ($val < 1 || $val > 40000) {
            fwrite(STDERR, "Error: {$name} must be between 1 and 40000, got {$val}\n");
            exit(1);
        }
    }

    try {
        echo $solver->solve($a, $b, $c) . "\n";
    } catch (\InvalidArgumentException $e) {
        fwrite(STDERR, "Error: " . $e->getMessage() . "\n");
        exit(1);
    }
}
