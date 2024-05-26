<?php
//
//declare(strict_types=1);
//
//namespace Renakdup\CacheWarmUp\Test\Crawler;
//
//use GuzzleHttp\Client;
//use PHPUnit\Framework\TestCase;
//use Renakdup\CacheWarmUp\Console\ConsoleDTO;
//use Renakdup\CacheWarmUp\Crawler\SitemapCrawler;
//use Renakdup\CacheWarmUp\Parser\ParserInterface;
//use Renakdup\SimpleDIC\Container;
//
//class CrawlerTest extends TestCase
//{
//    private Container $c;
//
//    protected function setUp(): void
//    {
//        parent::setUp();
//        $this->c = new Container();
//    }
//
//    protected function tearDown(): void
//    {
//        parent::tearDown();
//        unset($this->c);
//    }
//
//    public function testRun()
//    {
////        private ConsoleDTO $console_DTO, // TODO:: should be config
////        private Client $client,
////        private ParserInterface $parser
//        $this->c->
//
//
//        /**
//         * @var SitemapCrawler $crawler
//         */
//        $crawler = $this->c->get(SitemapCrawler::class);
//        $page_urls = $crawler->run();
//
//        dump($page_urls);
//        exit;
//    }
//
//    public function runDataProvider()
//    {
//        []
//    }
//}