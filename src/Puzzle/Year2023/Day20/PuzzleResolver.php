<?php

namespace App\Puzzle\Year2023\Day20;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2023/day/20
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 11687500;
    protected static int|string $testPart2Expected = 0;

    protected static int|string $part1Expected = 806332748;
    protected static int|string $part2Expected = 228060006554227;

    public const TYPE_CONJUNCTION = '&';
    public const TYPE_FLIP_FLOP = '%';
    public const TYPE_BROADCASTER = 'broadcaster';
    public const LOW_PULSE = 'low';
    public const HIGH_PULSE = 'high';

    public function part1(): int
    {
        $data = $this->getInput()->getArrayData();
        $modules = $this->parse($data);
        $pulses = [self::LOW_PULSE => 0, self::HIGH_PULSE => 0];

        for ($i = 0; $i < 1000; ++$i) {
            [$pulses, $modules] = $this->push($modules, $pulses);
        }

        return array_product($pulses);
    }

    // TODO need to find a better way, take too long .....
    public function part2(): int
    {
        $data = $this->getInput()->getArrayData();
        $modules = $this->parse($data);
        $pulses = [self::LOW_PULSE => 0, self::HIGH_PULSE => 0];
        $buttonPresses = 0;

        if ($this->isTestMode()) {
            $this->getOutput()->writeln('unable to process test mode RX not defined');

            return 0;
        }

        while (true) {
            [$pulses, $modules, $rxReceivedLowPulse] = $this->push($modules, $pulses);

            if ($rxReceivedLowPulse) {
                break;
            }
            ++$buttonPresses;
        }

        return $buttonPresses;
    }

    public function parse(array $data): array
    {
        $modules = [];

        foreach ($data as $line) {
            [$a, $b] = explode(' -> ', $line);

            $type = (self::TYPE_BROADCASTER === $a) ? self::TYPE_BROADCASTER : substr($a, 0, 1);
            $name = (self::TYPE_BROADCASTER === $a) ? self::TYPE_BROADCASTER : substr($a, 1);

            $modules[$name] = [
                'type' => $type,
                'outputs' => explode(', ', $b),
                'state' => false,
                'incoming_pulses' => [],
            ];
        }

        return $modules;
    }

    private function push(array $modules, array $pulses): array
    {
        $queue = new \SplQueue();
        $queue->enqueue([null, 'broadcaster', self::LOW_PULSE]);
        ++$pulses[self::LOW_PULSE];
        $rxReceivedLowPulse = false;

        while (!$queue->isEmpty()) {
            [$from , $to, $pulse] = $queue->dequeue();

            // MODULE rx, check for a single low pulse
            if ('rx' === $to && self::LOW_PULSE === $pulse) {
                $rxReceivedLowPulse = true;
                break;
            }

            if (!isset($modules[$to])) {
                continue;
            }

            $currentType = $modules[$to]['type'];

            $currentOutputs = $modules[$to]['outputs'];

            // MODULE Broadcast, forward pulse
            if (self::TYPE_BROADCASTER === $currentType) {
                foreach ($currentOutputs as $output) {
                    ++$pulses[$pulse];
                    $queue->enqueue([$to, $output, $pulse]);
                }
                continue;
            }

            // MODULE Flip-flop (%) on or off, default at off
            // TODO store incoming pulse
            // incoming high pulse => ignore
            // incoming low pulse => switch on/off
            // if current state is off => send high pulse
            // if current state is on => send low pulse
            if (self::TYPE_FLIP_FLOP === $currentType) {
                if (self::HIGH_PULSE === $pulse) {
                    continue;
                }

                $forwardPulse = $modules[$to]['state'] ? self::LOW_PULSE : self::HIGH_PULSE;
                $modules[$to]['state'] = !$modules[$to]['state'];

                foreach ($currentOutputs as $output) {
                    ++$pulses[$forwardPulse];
                    $queue->enqueue([$to, $output, $forwardPulse]);
                }

                continue;
            }

            // MODULE Conjunction (&) check last pulse of incoming module
            // Conjunction modules (prefix &) remember the type of the most recent pulse received from each of their connected input modules;
            // they initially default to remembering a low pulse for each input.
            // When a pulse is received, the conjunction module first updates its memory for that input.
            // Then, if it remembers high pulses for all inputs, it sends a low pulse; otherwise, it sends a high pulse.
            if (self::TYPE_CONJUNCTION === $currentType) {
                if (empty($modules[$to]['incoming_pulses'])) {
                    $modules[$to]['incoming_pulses'] = $this->getIncomingArray($to, $modules);
                }

                $modules[$to]['incoming_pulses'][$from] = $pulse;

                $forwardPulse = 0 === count(array_filter($modules[$to]['incoming_pulses'], fn ($e) => self::LOW_PULSE === $e)) ? self::LOW_PULSE : self::HIGH_PULSE;

                foreach ($currentOutputs as $output) {
                    ++$pulses[$forwardPulse];
                    $queue->enqueue([$to, $output, $forwardPulse]);
                }
            }
        }

        return [$pulses, $modules, $rxReceivedLowPulse];
    }

    private function getIncomingArray(string $name, array $modules): array
    {
        $incoming = [];
        foreach ($modules as $key => $data) {
            if (in_array($name, $data['outputs'])) {
                $incoming[$key] = self::LOW_PULSE;
            }
        }

        return $incoming;
    }
}
