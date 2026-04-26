# Water Jug Problem

Given two vessels of capacity **a** and **b** litres, find the minimum number of steps
to obtain exactly **c** litres in either vessel, starting with both empty.

Allowed operations (each counts as one step):

- Fill a vessel to capacity
- Empty a vessel completely
- Pour from one vessel into the other until the source is empty or the destination is full

## Approach

### Feasibility check (O(log min(a,b)))

A target `c` is achievable if and only if:

1. `c ≤ max(a, b)` — you can't hold more than the larger vessel, and
2. `c % gcd(a, b) == 0` — by Bézout's identity, every amount reachable through
   filling/emptying/pouring is a multiple of `gcd(a, b)`.

If either condition fails, the answer is `-1` immediately, no search needed.

### Minimum steps

Two strategies are available, both guaranteed to return the same result:

**BFS** (`BreadthFirstSearch`, the default): explores all pairs `(amountInA, amountInB)`
from `(0, 0)`. Each state has at most six neighbours (fill A, fill B, empty A, empty B,
pour A→B, pour B→A). BFS guarantees the first path found is the shortest.
Runs in **O(a × b)** time and space.

**Canonical sequence simulation** (`CanonicalSequenceStrategy`): simulates the two canonical pouring sequences
algebraically — fill A first, or fill B first — and returns the shorter one. Every
BFS-optimal path belongs to one of these two families, so `min(stepsFillingA, stepsFillingB)`
always equals the BFS minimum. Runs in **O(max(a, b))** time and **O(1)** space.

## Requirements

- PHP 8.2+
- Composer

## Installation

```bash
composer install
```

## Usage

Input format: one integer `t` (number of test cases), followed by `t` groups of three
integers `a`, `b`, `c` on separate lines.

```bash
php bin/solve.php < input.txt
```

Example `input.txt`:

```
2
5
2
3
2
3
4
```

Expected output:

```
2
-1
```

## Tests

```bash
./vendor/bin/phpunit
```

## Static analysis

```bash
./vendor/bin/phpstan analyse
```
