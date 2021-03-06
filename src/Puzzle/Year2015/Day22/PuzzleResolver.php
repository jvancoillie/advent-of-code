<?php

namespace App\Puzzle\Year2015\Day22;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver
 * @see https://adventofcode.com/2015/day/22
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    private $boss = [
        'Hit'    => 0,
        'Damage' => 0,
        'Armor'  => 0,
    ];

    private $player = [
        'Hit'      => 50,
        'Damage'   => 0,
        'Armor'    => 0,
        'Mana'     => 500,
        'Cost'     => 0,
        'Spent' => 0,
        'Shield' => 0,
        'Poison' => 0,
        'Recharge' => 0,

    ];

    private $spells = [
        'Missile'  => ['Cost' => 53,  'Active' => 0, 'Damage' => 4, 'Heal' => 0, 'Armor' => 7 , 'Mana' => 0 ],
        'Drain'    => ['Cost' => 73,  'Active' => 0, 'Damage' => 2, 'Heal' => 2, 'Armor' => 7 , 'Mana' => 0 ],
        'Shield'   => ['Cost' => 113, 'Active' => 6, 'Damage' => 0, 'Heal' => 0, 'Armor' => 7 , 'Mana' => 0 ],
        'Poison'   => ['Cost' => 173, 'Active' => 6, 'Damage' => 3, 'Heal' => 0, 'Armor' => 7 , 'Mana' => 0 ],
        'Recharge' => ['Cost' => 229, 'Active' => 5, 'Damage' => 0, 'Heal' => 0, 'Armor' => 7 , 'Mana' => 101 ],
    ];

    public function main(PuzzleInput $input, OutputInterface $output, $options = [])
    {
        dump($options);
        if($options['env'] === 'test'){
            $this->player['Hit'] = 10;
            $this->player['mana'] = 250;
        }

        $this->setBossStats($input);

        $this->part1($input, $output);
        $this->part2($input, $output);
    }

    public function part1(PuzzleInput $input, OutputInterface $output)
    {

        $ans = $this->play();

        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(PuzzleInput $input, OutputInterface $output)
    {
        $ans = $this->play(true);
        $output->writeln("<info>Part 2 : $ans</info>");
    }

    private function setBossStats(PuzzleInput $input)
    {
        foreach (explode("\n", $input->getData()) as $line) {
            if(preg_match('/^Hit Points:\s(\d+)$/', $line, $matches)){
                $this->boss['Hit'] = (int) $matches[1];
            }
            if(preg_match('/^Damage:\s(\d+)$/', $line, $matches)){
                $this->boss['Damage'] = (int) $matches[1];
            }
        }
    }

    private function play($hard = false)
    {
        $min = INF;

        $queue = new \SplQueue();
        $queue->enqueue([
            'Boss' => $this->boss,
            'Player' => $this->player,
            'Turn' => 'Player',
        ]);

        while (!$queue->isEmpty()) {
            $round = $queue->dequeue();
            if ($hard && $round['Turn'] == 'Player'){
                $round['Player']['Hit']--;
            }

            $round['Player']['Armor'] = ($round['Player']['Shield']-- > 0 ? $this->spells['Shield']['Armor'] : 0);

            if ($round['Player']['Poison']-- > 0){
                $round['Boss']['Hit'] -= $this->spells['Poison']['Damage'];
            }

            if ($round['Player']['Recharge']-- > 0){
                $round['Player']['Mana'] += $this->spells['Recharge']['Mana'];
            }

            if ($round['Player']['Hit'] <= 0 || $round['Player']['Spent'] >= $min){
                continue;
            }
            if ($round['Boss']['Hit'] <= 0) {
                $min = min($min, $round['Player']['Spent']);
                continue;
            }

            if ($round['Turn'] == 'Boss') {
                $round['Turn'] = 'Player';
                $round['Player']['Hit'] -= max(1, $round['Boss']['Damage'] - $round['Player']['Armor']);
                $queue->enqueue($round);
            } else {
                $round['Turn'] = 'Boss';
                foreach ($this->spells as $spell => $info) {
                    if ($info['Cost'] >= $round['Player']['Mana']){
                        continue;
                    }

                    $nextRound = $round;
                    $nextRound['Player']['Mana'] -= $info['Cost'];
                    $nextRound['Player']['Spent'] += $info['Cost'];

                    switch ($spell) {
                        case 'Missile':
                        case 'Drain':
                            $nextRound['Boss']['Hit'] -= $info['Damage'];
                            $nextRound['Player']['Hit'] += $info['Heal'];
                            break;
                        default:
                            if ($nextRound['Player'][$spell] > 0){
                                continue 2;
                            }
                            $nextRound['Player'][$spell] = $info['Active'];
                            break;
                    }
                    $queue->enqueue($nextRound);
                }
            }
        }

        return $min;
    }
}