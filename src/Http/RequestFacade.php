<?php

declare(strict_types=1);

namespace Renakdup\CacheWarmUp\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Client\ClientExceptionInterface;
use Renakdup\CacheWarmUp\Console\ConsoleDTO;

class RequestFacade
{
    private int $req_count = 0;

    public function __construct(
        private ConsoleDTO $consoleDTO,
        private Client $client,
    ) {
    }

    public function sendRequests($urls, bool $is_sitemap = false): array
    {
        $client = $this->client;
        $requests = function () use ($client, $urls) {
            for ($i = 0; $i < count($urls); $i++) {
                if ($this->consoleDTO->delay !== 0 && $this->req_count >= $this->consoleDTO->concurrency) {
                    //$milliseconds_in_second = 1000000;
                    echo "Delay {$this->consoleDTO->delay} sec\n";
                    sleep($this->consoleDTO->delay);
                    $this->req_count = 0;
                }

                yield function () use ($client, $urls, $i) {
                    return $client->getAsync($urls[$i]);
                };
                $this->req_count++;
            }
        };

        $success_responses = [];

        $pool = new Pool($client, $requests(), [
            'concurrency' => $this->consoleDTO->concurrency,
            'timeout' => $this->consoleDTO->timeout,
            'fulfilled' => function (Response $response, $index) use ($urls, &$success_responses, $is_sitemap) {
                $xcache_header = implode(',', $response->getHeader('X-Cache'));
                $cloudfront_header = implode(',', $response->getHeader('Cf-Cache-Status'));

                if ($is_sitemap) {
                    echo "{$response->getStatusCode()} | {$urls[$index]}\n";
                    $success_responses[$index] = (string)$response->getBody();
                } else {
                    echo "{$response->getStatusCode()} | " .
                        "X-Cache: " . str_pad(
                            substr($xcache_header, 0, 8),
                            8
                        ) . " | Cf-Cache-Status: " .
                        str_pad(
                            substr($cloudfront_header, 0, 7),
                            7
                        ) .
                        " | {$urls[$index]}\n";
                }
            },
            'rejected' => function (ClientExceptionInterface $exception, $index) use ($urls) {
                if ($exception instanceof RequestException) {
                    $response = $exception->getResponse();
                    echo "{$response->getStatusCode()} | {$urls[$index]}\n";
                    return;
                }

                echo $exception->getMessage() . "\n";
            }
        ]);

        $promise = $pool->promise();
        $promise->wait();
        return $success_responses;
    }
}