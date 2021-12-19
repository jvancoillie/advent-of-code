<?php

namespace App\Puzzle\Year2021\Day19;

use App\Puzzle\AbstractPuzzleResolver;
use App\Utils\Distance;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2021/day/19
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 79;
    protected static int|string $testPart2Expected = 3621;

    protected static int|string $part1Expected = 383;
    protected static int|string $part2Expected = 9854;

    /**
     * @var \SplQueue|Scanner[]
     */
    protected \SplQueue $locked;

    /**
     * @var \SplQueue|Scanner[]
     */
    protected \SplQueue $unlocked;

    protected function initialize(): void
    {
        $this->locked = new \SplQueue();
        $this->unlocked = new \SplQueue();

        $data = explode("\n\n", $this->getInput()->getData());

        foreach ($data as $scannerData) {
            $scanner = $this->parseScannerData($scannerData);
            if ($scanner->isLocked()) {
                $this->locked->enqueue($scanner);
            } else {
                $this->unlocked->enqueue($scanner);
            }
        }

        while (!$this->unlocked->isEmpty()) {
            $unlockedScanner = $this->unlocked->dequeue();

            $found = false;
            foreach ($this->locked as $lockedScanner) {
                if ($lockedScanner->tryAlign($unlockedScanner)) {
                    $found = true;
                    $this->locked->enqueue($unlockedScanner);
                    break;
                }
            }
            if (!$found) {
                $this->unlocked->enqueue($unlockedScanner);
            }
        }
    }

    public function part1(): int
    {
        $beacons = [];

        foreach ($this->locked as $scanner) {
            foreach ($scanner->getPointsFromPosition() as $p) {
                $beacons[$p->getName()] = $p->getName();
            }
        }

        return count($beacons);
    }

    public function part2(): int|float
    {
        $max = 0;

        foreach ($this->locked as $scannerA) {
            foreach ($this->locked as $scannerB) {
                if ($scannerA === $scannerB) {
                    continue;
                }
                $dist = Distance::manhattan($scannerA->getPosition()->toArray(), $scannerB->getPosition()->toArray());
                $max = max($dist, $max);
            }
        }

        return $max;
    }

    public function parseScannerData(string $scannerData): Scanner
    {
        $data = explode("\n", $scannerData);
        $scannerInfo = array_shift($data);
        preg_match("/--- scanner (?<id>\d+) ---/", $scannerInfo, $m);

        $scanner = new Scanner($m['id']);
        foreach ($data as $pointData) {
            [$x, $y, $z] = explode(',', $pointData);
            $scanner->addPoint(new Point((int) $x, (int) $y, (int) $z));
        }

        return $scanner;
    }
}
