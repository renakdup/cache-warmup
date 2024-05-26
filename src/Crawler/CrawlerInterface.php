<?php

namespace Renakdup\CacheWarmUp\Crawler;

interface CrawlerInterface
{
    public function run( array $urls );
}