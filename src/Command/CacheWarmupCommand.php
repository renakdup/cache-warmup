<?php

namespace Renakdup\CacheWarmUp\Command;

use GuzzleHttp\Client;
use Renakdup\CacheWarmUp\Crawler\SitemapCrawler;
use Renakdup\CacheWarmUp\Console\ConsoleDTO;
use Renakdup\CacheWarmUp\Console\HydratorConsole;
use Renakdup\CacheWarmUp\Http\RequestFacade;
use Renakdup\CacheWarmUp\Sitemap;
use Renakdup\SimpleDIC\Container;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CacheWarmupCommand extends Command
{
    public const ARG_URL = 'url';
    public const OPTION_CONCURRENCY = 'concurrency';
    public const OPTION_DELAY = 'delay';
    public const OPTION_TIMEOUT = 'timeout';
    public const OPTION_VERBOSE = 'verbose';

    public function __construct(
        private HydratorConsole $hydratorConsoleDTO,
        private ValidatorInterface $validator,
        private Container $c,
    ) {
        parent::__construct(name: 'cache-warmup');
    }

    protected function configure(): void
    {
        $this->addArgument(self::ARG_URL, InputArgument::REQUIRED, 'URL to Sitemap.xml');

        $this
            ->addOption(
                self::OPTION_CONCURRENCY,
                'c',
                InputOption::VALUE_OPTIONAL,
                'Count of requests that would be sent at the same time.',
                1,
            )
            ->addOption(
                self::OPTION_DELAY,
                'd',
                InputOption::VALUE_OPTIONAL,
                'Delay in seconds',
                0,
            )
            ->addOption(
                self::OPTION_TIMEOUT,
                't',
                InputOption::VALUE_OPTIONAL,
                'Timeout in seconds',
                10,
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $consoleDTO = $this->hydratorConsoleDTO->hydrate(
            $input->getArguments(),
            $input->getOptions(),
            new ConsoleDTO()
        );
        $this->c->set(ConsoleDTO::class, $consoleDTO);

        $violations = $this->validator->validate($consoleDTO);

        if (count($violations) > 0) {
            $this->print_command_errors($violations, $output);
            return Command::FAILURE;
        }

        $time_start = microtime(true);
        /** @var SitemapCrawler $sitemap_crawler */
        $sitemap_crawler = $this->c->make(SitemapCrawler::class);
        $page_urls = $sitemap_crawler->run(
            [$consoleDTO->url, 'https://xxxrwerq2rwqrwrq.com']
        );

        $this->print( '<fg=cyan>=============</>', $output);

        /** @var RequestFacade $pages_crawler */
        $pages_crawler = $this->c->make(RequestFacade::class);
        $pages_crawler->sendRequests($page_urls);

        $time = microtime(true) - $time_start;

        $this->print( '<fg=cyan>=============</>', $output);
        $output->writeln('<fg=black;bg=cyan>Count of pages</>: ' . count($page_urls) . '');
        $output->writeln('<fg=black;bg=cyan>Time</>: ' . round($time / 60, 2) . ' min');

        return Command::SUCCESS;
    }

    private function print_command_errors(ConstraintViolationListInterface $violations, OutputInterface $output): void
    {
        foreach ($violations as $violation) {
            $output->writeln(
                sprintf(
                    '<error>%s: %s</error>',
                    $violation->getPropertyPath(),
                    $violation->getMessage()
                )
            );
        }
    }

    private function print(string $text, OutputInterface $output)
    {
        /** @var ConsoleDTO $consoleDTO */
        $consoleDTO = $this->c->get(ConsoleDTO::class);
        if ($consoleDTO->verbose) {
            return;
        }

        $output->writeln($text);
    }
}