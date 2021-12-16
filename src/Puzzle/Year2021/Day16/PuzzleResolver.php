<?php

namespace App\Puzzle\Year2021\Day16;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2021/day/16
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 31;
    protected static int|string $testPart2Expected = 54;

    protected static int|string $part1Expected = 852;
    protected static int|string $part2Expected = 19348959966392;

    private const OPERATOR = 'OPERATOR';
    private const LITERAL = 'LITERAL';

    private array $result = [];

    public function initialize(): void
    {
        $data = str_split($this->getInput()->getData());
        $bits = '';
        foreach ($data as $e) {
            $bits .= sprintf('%04d', base_convert($e, 16, 2));
        }

        $this->result = $this->parse($bits);
    }

    public function part1(): int
    {
        return $this->result['version'];
    }

    public function part2(): int
    {
        return $this->result['result'];
    }

    public function getPacketInfo($bits): array
    {
        $start = 0;
        $version = bindec(substr($bits, $start, 3));
        $start += 3;
        $packetId = bindec(substr($bits, $start, 3));
        $start += 3;
        $remaining = '';

        $info = [
            'from_bits' => $bits,
            'current_pack' => $bits,
            'sub_packet' => '',
            'remaining' => '',
            'version' => $version,
            'type_id' => $packetId,
            'type' => (4 !== $packetId) ? self::OPERATOR : self::LITERAL,
            'operation_type' => null,
        ];

        if (4 !== $packetId) {
            $lengthId = substr($bits, $start, 1);
            ++$start;
            $length = '0' == $lengthId ? 15 : 11;
            $subPacketCount = bindec(substr($bits, $start, $length));
            $start += $length;
            $info['current_pack'] = substr($bits, 0, $start);
            if ('0' == $lengthId) {
                $info['operation_type'] = 'string';
                $info['length'] = $subPacketCount;

                $info['sub_packet'] = substr($bits, $start, $subPacketCount);
                $remaining = substr($bits, $start + $subPacketCount);
            } else {
                $info['operation_type'] = 'array';
                $info['length'] = $subPacketCount;
                $info['sub_packet'] = substr($bits, $start);
            }
        } else {
            $sequences = str_split(substr($bits, $start), 5);
            $result = [];
            while ($sequences) {
                $seq = array_shift($sequences);
                $result[] = substr($seq, 1);
                if ('1' !== $seq[0]) {
                    break;
                }
            }

            $info['result'] = bindec(join($result));
            $remaining = join($sequences);
        }

        if (array_sum(str_split($remaining)) > 0) {
            $info['remaining'] = $remaining;
        }

        return $info;
    }

    public function parse($bits): array
    {
        $packet = $this->getPacketInfo($bits);

        $children = [];

        if (self::OPERATOR === $packet['type']) {
            if ('array' === $packet['operation_type']) {
                $count = $packet['length'];
                $i = 0;
                $subPacket = $packet['sub_packet'];

                while ($i < $count) {
                    $subPacketInfo = $this->parse($subPacket);
                    $subPacket = $subPacketInfo['remaining'];
                    $children[] = $subPacketInfo;
                    ++$i;
                }
            } elseif ('string' === $packet['operation_type']) {
                $subPacket = $packet['sub_packet'];
                while ('' !== $subPacket) {
                    $subPacketInfo = $this->parse($subPacket);
                    $subPacket = $subPacketInfo['remaining'];
                    $children[] = $subPacketInfo;
                }
            }
        }

        if ($children && 'array' === $packet['operation_type']) {
            $packet['remaining'] = end($children)['remaining'];
        }

        $results = [];
        $sumVersion = $packet['version'];

        foreach ($children as $child) {
            $results[] = $child['result'];
            $sumVersion += $child['version'];
        }
        if (empty($results)) {
            $results[] = $packet['result'] ?? 0;
        }

        $result = match ($packet['type_id']) {
            0 => array_sum($results),
            1 => array_product($results),
            2 => min($results),
            3 => max($results),
            5 => $results[0] > $results[1] ? 1 : 0,
            6 => $results[0] < $results[1] ? 1 : 0,
            7 => $results[0] === $results[1] ? 1 : 0,
            default => $results[0],
        };

        $packet['result'] = $result;
        $packet['children'] = $children;
        $packet['version'] = $sumVersion;

        return $packet;
    }
}
