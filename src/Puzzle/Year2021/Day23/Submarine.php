<?php

namespace App\Puzzle\Year2021\Day23;

class Submarine
{
    private array $hallway;

    private int $cost = 0;

    private array $moveCost = [
        'A' => 1,
        'B' => 10,
        'C' => 100,
        'D' => 1000,
    ];

    private array $order = [
        2 => 'A',
        4 => 'B',
        6 => 'C',
        8 => 'D',
    ];

    private int $roomLength;

    public function __construct(private array $rooms)
    {
        $this->roomLength = count(reset($rooms));

        $this->hallway = array_fill(0, 11, '.');
    }

    public function getCost(): int
    {
        return $this->cost;
    }

    /**
     *  hallway [ . .  X  .  X  .  X .  X  .   .  ]
     *                0 1    3     5    7     9 10
     *                      2     4    6     8.
     */
    public function getNextMoves(): array
    {
        $moves = [];

        foreach ($this->hallway as $hallwayId => $amphi) {
            // no amphi on index continue;
            if ('.' === $amphi) {
                continue;
            }

            // check right
            for ($right = $hallwayId + 1; $right < count($this->hallway); ++$right) {
                if ($this->canFill($right, $amphi)) {
                    return [['from' => $hallwayId, 'to' => $right]];
                }

                if ('.' !== $this->hallway[$right]) {
                    break;
                }
            }

            for ($left = $hallwayId - 1; $left >= 0; --$left) {
                if ($this->canFill($left, $amphi)) {
                    return [['from' => $hallwayId, 'to' => $left]];
                }

                if ('.' !== $this->hallway[$left]) {
                    break;
                }
            }
        }

        foreach ($this->rooms as $roomId => $room) {
            if (!$this->isMovableAmphi($roomId)) {
                continue;
            }

            $amphi = reset($room);

            for ($right = $roomId; $right < count($this->hallway); ++$right) {
                if ($this->isFillablePosition($right)) {
                    if ($this->canFill($right, $amphi)) {
                        //                        return [['from' => $roomId, 'to' => $right]];
                        return [['from' => $roomId, 'to' => $right]];
                    }
                    continue;
                }

                if ('.' !== $this->hallway[$right]) {
                    break;
                }

                $moves[] = ['from' => $roomId, 'to' => $right];
            }

            for ($left = $roomId; $left >= 0; --$left) {
                if ($this->isFillablePosition($left)) {
                    if ($this->canFill($left, $amphi)) {
                        // return [];
                        return [['from' => $roomId, 'to' => $left]];
                    }
                    continue;
                }

                if ('.' !== $this->hallway[$left]) {
                    break;
                }

                $moves[] = ['from' => $roomId, 'to' => $left];
            }
        }

        return $moves;
    }

    public function canFill($position, $amphi): bool
    {
        return $this->isFillablePosition($position) && $this->order[$position] === $amphi && !$this->isMovableAmphi($position);
    }

    private function isFillablePosition($position): bool
    {
        return in_array($position, [2, 4, 6, 8]);
    }

    public function isMovableAmphi($roomId): bool
    {
        if (0 === count($this->rooms[$roomId])) {
            return false;
        }

        $correctAmphi = $this->order[$roomId];

        $reversed = array_reverse($this->rooms[$roomId]);

        foreach ($reversed as $amphi) {
            if ($amphi !== $correctAmphi) {
                return true;
            }
        }

        return false;
    }

    public function applyMove(array $move)
    {
        $distance = abs($move['from'] - $move['to']);
        if (in_array($move['from'], ['2', '4', '6', '8'])) {
            $distance += ($this->roomLength - count($this->rooms[$move['from']]));
            $amphi = array_shift($this->rooms[$move['from']]);
        } else {
            $amphi = $this->hallway[$move['from']];
            $this->hallway[$move['from']] = '.';
        }

        if (in_array($move['to'], ['2', '4', '6', '8'])) {
            $distance += ($this->roomLength + 1 - count($this->rooms[$move['to']]));
            array_unshift($this->rooms[$move['to']], $amphi);
        } else {
            $this->hallway[$move['to']] = $amphi;
        }

        $this->cost += $distance * $this->moveCost[$amphi];
    }

    public function isEnd(): bool
    {
        foreach ($this->rooms as $roomId => $room) {
            if ($this->roomLength !== count($room)) {
                return false;
            }

            if ($room[0] !== $this->order[$roomId]) {
                return false;
            }
        }

        return true;
    }

    public function getStateHash()
    {
        return json_encode(['room' => $this->rooms, 'hallway' => $this->hallway], JSON_THROW_ON_ERROR);
    }
}
