<?php

declare(strict_types=1);

namespace Renakdup\CacheWarmUp\Crawler;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Promise\Utils;
use GuzzleHttp\Psr7\Response;
use Renakdup\CacheWarmUp\Console\ConsoleDTO;
use Renakdup\CacheWarmUp\Log\LoggerInterface;
use Renakdup\CacheWarmUp\Parser\ParserInterface;
use Renakdup\CacheWarmUp\Parser\Xml;

class Crawler implements CrawlerInterface
{
    private $data = [];

    public function __construct(
        private ConsoleDTO $console_DTO, // TODO:: should be config
        private Client $client,
        private ParserInterface $parser
        //private LoggerInterface $logger,
    )
    {
    }

    public function run()
    {
        $sitemap_urls = [$this->console_DTO->url];
        $pages_urls = [];

        $i = 0;
        while (count($sitemap_urls)) {
            $this->crawl($sitemap_urls, $pages_urls);
            unset($sitemap_urls[$i]);
            $i++;
        }

        dump($pages_urls);
        exit;
    }

    private function crawl(array $sitemap_urls, &$pages_urls): array
    {
        if (empty($sitemap_urls)) {
            return [];
        }

        $client = $this->client;

        $requests = function () use ($client, $sitemap_urls) {
            for ($i = 0; $i < count($sitemap_urls); $i++) {
                yield function () use ($client, $sitemap_urls, $i) {
                    return $client->getAsync($sitemap_urls[$i]);
                };
            }
        };

        $responses = [];

        $pool = new Pool($client, $requests(), [
            'fulfilled' => function (Response $response, $index) use (&$responses) {
                // Это обработчик успешно выполненных запросов
                $responses[$index] = (string)$response->getBody();
            },
            'rejected' => function (RequestException $exception, $index) {
                // Это обработчик ошибок запросов
                // В данном примере просто игнорируется
            }
        ]);

        $promise = $pool->promise();
        $promise->wait();

        $parsed_links = $this->parser->parse($responses[0]);
        dump($new_links);
        exit;

        dump($responses);

        return [];
    }

    private function segregate_urls(array $new_urls, array &$sitemap_urls, array &$pages_urls): void
    {
        foreach ($new_urls as $url) {
            if (str_ends_with(mb_strtolower($url), '.xml')) {
                $sitemap_urls[] = $url;
                continue;
            }

            $pages_urls[] = $url;
        }
    }
}