<?php

declare(strict_types=1);

namespace Renakdup\CacheWarmUp\Crawler;

use GuzzleHttp\Client;
use Renakdup\CacheWarmUp\Console\ConsoleDTO;
use Renakdup\CacheWarmUp\Http\RequestFacade;
use Renakdup\CacheWarmUp\Parser\ParserInterface;

class SitemapCrawler implements CrawlerInterface
{
    private array $data = [];

    private array $failed_requests = [];
    private ConsoleDTO $consoleDTO;
    private Client $client;
    private ParserInterface $parser;
    private RequestFacade $request;

    public function __construct(
        ConsoleDTO $consoleDTO, // TODO:: should be config
        Client $client,
        ParserInterface $parser,
        RequestFacade $request
    ) {
        $this->request = $request;
        $this->parser = $parser;
        $this->client = $client;
        $this->consoleDTO = $consoleDTO;
    }

    public function run(array $urls): array
    {
        if (!$urls) {
            return [];
        }
        $pages_urls = [];

        while (count($urls)) {
            $chunk_urls = array_splice($urls, array_key_first($urls), $this->consoleDTO->concurrency);
            $new_urls = $this->crawl($chunk_urls);
            $this->mergeUrls($new_urls, $urls, $pages_urls);
        }

        sort($pages_urls);

        return $pages_urls;
    }

    private function crawl(array $chunk_urls): array
    {
        if (empty($chunk_urls)) {
            return [];
        }

        $responses = $this->request->sendRequests($chunk_urls, true);

        if (count($responses) === 0) {
            return [];
        }

        $urls = [];
        foreach ($responses as $response) {
            $urls = array_merge($urls, $this->parser->parse($response));
        }

        return $urls;
    }

    private function mergeUrls(array $new_urls, array &$sitemap_urls, array &$pages_urls): void
    {
        foreach ($new_urls as $url) {
            if (in_array($url, $sitemap_urls) || in_array($url, $pages_urls)) {
                continue;
            }

            if (str_ends_with($url, '.xml')) {
                $sitemap_urls[] = $url;
                continue;
            }

            $pages_urls[] = $url;
        }
    }

    public function getFailedRequests(): array
    {
        return $this->failed_requests;
    }
}