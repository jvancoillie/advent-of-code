<?php

namespace App\Command;

use App\Utils\Leaderboard;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
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
            ->addOption('all', null, InputOption::VALUE_NONE, 'display all days')
            ->addOption('no-cache', null, InputOption::VALUE_NONE, 'without cache')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $year = $input->getOption('year');
        $days = [$input->getOption('day')];

        if ($input->getOption('all')) {
            $days = range(1, 25);
        }

        $dotenv = new Dotenv();
        $dotenv->loadEnv(__DIR__.'/../../.env');

        $sessionId = $_ENV['AOC_SESSSION_ID'];
        $boardId = $_ENV['AOC_BOARD_ID'];

        $link = sprintf('https://adventofcode.com/%d/leaderboard/private/view/%d.json', $year, $boardId);

        if ('' === $sessionId) {
            $output->writeln(
                "<info> --- can't retrieve private leaderboard data, the AOC_SESSSION_ID must be set in the .env file --- <info>"
            );

            return Command::FAILURE;
        }
        try {
            $output->writeln(sprintf('<info>--- %s --- <info>', $link));

            $cacheKey = sprintf('private_leaderboard_%s_%s', $year, $boardId);

            if ($input->getOption('no-cache')) {
                $this->cache->delete($cacheKey);
            }

            $data = $this->cache->get($cacheKey, function (ItemInterface $item) use ($link, $sessionId) {
                $item->expiresAfter(900);
                try {
                    $response = $this->client->request('GET', $link, [
                            'headers' => ['Cookie' => 'session='.$sessionId],
                        ]);

                    return json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
                } catch (\Exception $e) {
                    $item->expiresAfter(0);

                    return $e;
                }
            });
        } catch (\Error) {
            $output->writeln(
                    sprintf('<error>Error when retrieve private leaderboard data for id %d and year %d</error>', $boardId, $year)
                );

            return Command::FAILURE;
        }

        $leaderboard = new Leaderboard($data);

        foreach ($days as $day) {
            $this->displayDayBoard($output, $leaderboard, $day);
        }

        return Command::SUCCESS;
    }

    protected function displayDayBoard(OutputInterface $output, Leaderboard $leaderboard, string $day): void
    {
        $table = new Table($output);

        $table->setHeaders([
            [new TableCell(sprintf(" %s - %s private leaderboard's", $leaderboard->getEvent(), $leaderboard->getOwnerName()), ['colspan' => 3])],
            [sprintf('Day %02d', $day),
                ...array_map(fn($i) => 'part '.$i % 2 + 1, range(0, 1)), ],
            ]);

        $leaderboard->walk(function ($entry) use ($table) {
            $table->addRow([
                        sprintf('%02d - %s', $entry['day_score'], $entry['member_name']),
                        sprintf('%d - %s', $entry['parts'][1]['part_score'], $entry['parts'][1]['elapsed']),
                        sprintf('%d - %s', $entry['parts'][2]['part_score'], $entry['parts'][2]['elapsed']),
                    ]);
        }, $day);

        $table->render();
    }
}
