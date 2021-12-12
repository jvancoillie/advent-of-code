<?php

namespace App\Utils;

class Leaderboard
{
    private array $members = [];
    private string $event;
    private string $ownerId;
    private array $dayBoard = [];

    public function __construct($data)
    {
        $this->members = $data['members'];
        $this->event = $data['event'];
        $this->ownerId = $data['owner_id'];
        $this->parse();
    }

    private function parse(): void
    {
        $memberCount = count($this->members);
        $days = [];

        foreach (range(1, 25) as $day) {
            $isDayResolvedByMember = false;
            $dayParts = [
                1 => [],
                2 => [],
            ];
            foreach ($this->members as $id => $member) {
                $partData = ['ts' => INF, 'member_id' => $id, 'part_score' => 0, 'elapsed' => '-'];
                $current = [1 => INF, 2 => INF];
                if (isset($member['completion_day_level'][$day])) {
                    $isDayResolvedByMember = true;
                    $current = array_replace($current, array_map(fn ($item) => $item['get_star_ts'], $member['completion_day_level'][$day]));
                }
                foreach ($current as $part => $ts) {
                    $partData['ts'] = $ts;
                    $partData['elapsed'] = '-';
                    if (INF !== $ts) {
                        $partData['elapsed'] = $this->elapsed($ts, $day);
                    }
                    $dayParts[$part][] = $partData;
                }
            }
            if ($isDayResolvedByMember) {
                for ($part = 1; $part <= 2; ++$part) {
                    usort($dayParts[$part], fn ($a, $b) => $a['ts'] <=> $b['ts']);
                    $penalty = 0;
                    foreach ($dayParts[$part] as $key => $entry) {
                        $score = 0;
                        if (INF !== $entry['ts']) {
                            $score = $memberCount - $penalty++;
                        }

                        $dayParts[$part][$key] = array_merge($dayParts[$part][$key], ['part_score' => $score, 'part' => $part]);
                    }
                }
            }

            $days[$day] = $dayParts;
        }
        foreach ($days as $day => $parts) {
            foreach ($this->members as $id => $member) {
                $mapped = array_map(function ($data) use ($id) {
                    $filtered = array_filter($data, fn ($entry) => $entry['member_id'] === $id);

                    return array_shift($filtered);
                }, $parts);

                $dayScore = array_reduce($mapped, fn ($carry, $item) => $carry + $item['part_score']);

                $this->dayBoard[$day][$id] = ['day_score' => $dayScore, 'parts' => $mapped, 'member_name' => $this->members[$id]['name']];
            }
            uasort($this->dayBoard[$day], fn ($a, $b) => $b['day_score'] <=> $a['day_score']);
        }
    }

    public function getOwnerName()
    {
        return $this->members[$this->ownerId]['name'];
    }

    public function getBoard(): array
    {
        return $this->dayBoard;
    }

    private function elapsed($timestamp, $day): string
    {
        try {
            $startDate = new \DateTimeImmutable(sprintf('%s-12-%s midnight', $this->event, $day));
            $doneAt = new \DateTimeImmutable('@'.$timestamp);
        } catch (\Exception) {
            return ' error ';
        }

        $diff = $startDate->diff($doneAt);

        return sprintf('+%02d:%02d:%02d', $diff->days * 24 + $diff->h, $diff->i, $diff->s);
    }

    public function getEvent()
    {
        return $this->event;
    }

    public function walk(callable $callable, $day)
    {
        array_walk($this->dayBoard[$day], $callable);
    }
}
