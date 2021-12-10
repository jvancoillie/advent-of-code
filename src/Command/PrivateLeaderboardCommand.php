<?php

namespace App\Command;

use App\Utils\Leaderboard;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PrivateLeaderboardCommand extends Command
{
    private HttpClientInterface $client;

    private FilesystemAdapter $cache;

    private string $year;
    private string $day;

    protected static $defaultName = 'puzzle:leaderboard';

    public function __construct()
    {
        parent::__construct();
        $this->client = HttpClient::create();
        $this->cache = new FilesystemAdapter();
    }

    protected function configure()
    {
        $currentYear = (new \DateTime())->format('Y');
        $currentDay = (new \DateTime())->format('d');

        $this
            ->setDescription('Display private leaderboard')
            ->addOption('year', 'y', InputOption::VALUE_REQUIRED, 'the year of the event', $currentYear)
            ->addOption('day', 'd', InputOption::VALUE_REQUIRED, 'the day of the event', $currentDay)
            ->addOption('no-cache', null, InputOption::VALUE_NONE, 'without cache')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->year = $input->getOption('year');
        $this->day = $input->getOption('day');

        $dotenv = new Dotenv();
        // loads .env, .env.local, and .env.$APP_ENV.local or .env.$APP_ENV
        $dotenv->loadEnv(__DIR__.'/../../.env');

        $sessionId = $_ENV['AOC_SESSSION_ID'];
        $boardId = $_ENV['AOC_BOARD_ID'];
        $link = sprintf('https://adventofcode.com/%d/leaderboard/private/view/%d.json', $this->year, $boardId);

        if ('' === $sessionId) {
            $output->writeln(
                "<info> --- can't retrieve private leaderboard data, the AOC_SESSSION_ID must be set in the .env file --- <info>"
            );

            return Command::FAILURE;
        }
        try {
            $output->writeln(sprintf('<info>--- %s --- <info>', $link));

            $cacheKey = sprintf('private_leaderboard_%s_%s', $this->year, $boardId);

            if ($input->getOption('no-cache')) {
                $this->cache->delete($cacheKey);
            }

            $data = $this->cache->get($cacheKey, function (ItemInterface $item) use ($link, $sessionId) {
                $item->expiresAfter(900);
                try {
                    $response = $this->client->request('GET', $link, [
                            'headers' => ['Cookie' => 'session='.$sessionId],
                        ]);

                    return json_decode($response->getContent(), true);
                } catch (\Exception $e) {
                    $item->expiresAfter(0);

                    return $e;
                }
            });
        } catch (\Error) {
            $output->writeln(
                    sprintf('<error>Error when retrieve private leaderboard data for id %d and  year %d</error>', $boardId, $year)
                );

            return Command::FAILURE;
        }

        $leaderboard = new Leaderboard($data);
        $table = new Table($output);

        $table->setHeaders([
            [new TableCell(sprintf(" %s - %s private leaderboard's", $leaderboard->getEvent(), $leaderboard->getOwnerName()), ['colspan' => 3])],
            [sprintf('Day %s', str_pad((string) $this->day, '2', '0', STR_PAD_LEFT)), ...array_map(function ($i) {return 'part '.$i % 2 + 1; }, range(0, 1))],
        ]);

        $leaderboard->walk(function ($entry) use ($table) {
            $table->addRow(
                [
                    sprintf('%d - %s', str_pad($entry['day_score'], 2, '0', STR_PAD_LEFT), $entry['member_name']),
                    sprintf('%d - %s', $entry['parts'][1]['part_score'], $entry['parts'][1]['elapsed']),
                    sprintf('%d - %s', $entry['parts'][2]['part_score'], $entry['parts'][2]['elapsed']),
                ]
            );
        }, $this->day);

        $table->render();

        return Command::SUCCESS;
    }

    protected function displayPerMembers(OutputInterface $output, array $board, mixed $data, mixed $members): void
    {
        $table = new Table($output);

        $headers = array_map(function ($e) {
            return sprintf('Day %s', str_pad((string) $e, '2', '0', STR_PAD_LEFT));
        }, range(1, count($board)));

        $table->setHeaders([
            [new TableCell(sprintf(" %s - %s private leaderboard's", $data['event'], $data['members'][$data['owner_id']]['name']), ['colspan' => count($headers) + 1])],
            [' ', ...$headers],
            [' ', ...array_map(function ($i) {return 'part '.$i % 2 + 1; }, range(0, count($board) * 2 - 1))],
        ]);

        $board = array_reverse($board, true);

        array_walk($members, function ($entry) use ($table, $board) {
            $days = [];
            foreach ($board as $day => $parts) {
                foreach ($parts as $part) {
                    if (isset($part[$entry['id']])) {
                        $time = $this->elapsed($part[$entry['id']]['ts'], $day);
                        $days[] = sprintf('%s - %d', $time, $part[$entry['id']]['part_score']);
                    } else {
                        $days[] = '  - ';
                    }
                }
            }
            $table->addRow(
                    [
                        $entry['name'],
                        ...$days,
                    ]
                );
        }
        );

        $table->render();
    }

    protected function displayPerDays(OutputInterface $output, array $board, mixed $data, mixed $members): void
    {
        $table = new Table($output);

        $headers = array_map(function ($e) {
            return $e['name'];
        }, $members);

        $scores = array_map(function ($e) {
            return $e['local_score'];
        }, $members);

        $table->setHeaders([
            [new TableCell(sprintf(" %s - %s private leaderboard's", $data['event'], $data['members'][$data['owner_id']]['name']), ['colspan' => count($headers) + 1])],
            ['', ...$headers],
            array_merge(['Total score'], $scores),
        ]);

        $board = array_reverse($board, true);

        array_walk($board, function ($entry, $day) use ($table, $members) {
            $parts = [];
            $day = str_pad($day, 2, 0, STR_PAD_LEFT);
            foreach ($members as $member) {
                foreach ($entry as $id => $part) {
                    $id = (int) $id;
                    $parts[$id][0] = sprintf('Day %s Part %d', $day, $id);
                    if (isset($part[$member['id']])) {
                        $time = $this->elapsed($part[$member['id']]['ts'], $day);
                        $parts[$id][] = sprintf('%s - %d', $time, $part[$member['id']]['part_score']);
                    } else {
                        $parts[$id][] = '  - ';
                    }
                }
            }
            $table->addRows([
                    ...$parts,
                    new TableSeparator(),
                ]);
        }
        );

        $table->render();
    }

    private function elapsed($timestamp, $day): string
    {
        $startDate = new \DateTimeImmutable(sprintf('%s-12-%s midnight', $this->year, $day));
        $doneAt = new \DateTimeImmutable('@'.$timestamp);

        $diff = $startDate->diff($doneAt);

        return sprintf('+%02d:%02d:%02d', $diff->days * 24 + $diff->h, $diff->i, $diff->s);
    }
}
