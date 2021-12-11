<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PuzzleMakerCommand extends Command
{
    /**
     * @var HttpClientInterface
     */
    private $client;

    /**
     * @var Filesystem
     */
    private $filesystem;

    protected static $defaultName = 'puzzle:make';

    public function __construct()
    {
        parent::__construct();
        $this->client = HttpClient::create();
        $this->filesystem = new Filesystem();
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $currentYear = (new \DateTime())->format('Y');
        $currentDay = (new \DateTime())->format('d');

        $this
            ->setDescription('Create the input data and structure for a given puzzle event')
            ->addOption('year', 'y', InputOption::VALUE_REQUIRED, 'the year of the event', $currentYear)
            ->addOption('day', 'd', InputOption::VALUE_REQUIRED, 'the day of the event', $currentDay)
            ->addOption('no-data', null, InputOption::VALUE_NONE, 'use this option to disable input data fetching')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $year = $input->getOption('year');
        $day = sprintf("%02d",$input->getOption('day'));

        $link = sprintf('https://adventofcode.com/%d/day/%d', $year, $day);

        $folderPath = sprintf('src/Puzzle/Year%d/Day%s', $year, $day);
        $namespace = sprintf("App\Puzzle\Year%d\Day%s", $year, $day);

        $inputFilePath = sprintf('%s/input/input.txt', $folderPath);
        $testFilePath = sprintf('%s/input/test.txt', $folderPath);
        $resolverFilePath = sprintf('%s/PuzzleResolver.php', $folderPath);

        $noData = $input->getOption('no-data');
        if ($this->filesystem->exists($resolverFilePath)) {
            $output->writeln('<comment> Enable to create Puzzle Resolver Class, already exist ! </comment>');

            return Command::FAILURE;
        }

        $output->writeln('<info>Create Puzzle Resolver Class</info>');
        $this->filesystem->dumpFile(
            $resolverFilePath,
            $this->parseTemplate(__DIR__.'/../Resources/skeleton/PuzzleResolver.tpl.php', [
                'namespace' => $namespace,
                'puzzleLink' => $link,
            ])
        );

        $output->writeln('<info>Create Puzzle data input files</info>');

        $this->filesystem->dumpFile($inputFilePath, '');
        $this->filesystem->dumpFile($testFilePath, '');

        if (!$noData) {
            $output->writeln(sprintf('<info>--- Retrieve data for DAY:  %1$s-%2$s --- <info>', $year, $day));

            $dotenv = new Dotenv();
            // loads .env, .env.local, and .env.$APP_ENV.local or .env.$APP_ENV
            $dotenv->loadEnv(__DIR__.'/../../.env');

            $sessionId = $_ENV['AOC_SESSSION_ID'];

            if ('' === $sessionId) {
                $output->writeln("<info> --- can't retrieve input data, the AOC_SESSSION_ID must be set in the .env.local file --- <info>");
            } else {
                try {
                    $inputDataLink = $link.'/input';
                    $output->writeln(sprintf('<info>--- %s --- <info>', $inputDataLink));
                    $response = $this->client->request('GET', $inputDataLink, [
                            'headers' => ['Cookie' => 'session='.$sessionId],
                        ]
                    );

                    $response->getContent();

                    $this->filesystem->dumpFile($inputFilePath, trim($response->getContent()));
                } catch (\Error) {
                    $output->writeln(
                        sprintf('<error>Error when retrieve input data for day %d of year %d</error>', $day, $year)
                    );

                    return Command::FAILURE;
                }
            }
        }

        return Command::SUCCESS;
    }

    public function parseTemplate(string $templatePath, array $parameters): string
    {
        ob_start();
        extract($parameters, EXTR_SKIP);
        include $templatePath;

        return ob_get_clean();
    }
}
