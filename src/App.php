<?php

declare(strict_types=1);

namespace Renakdup\CacheWarmUp;

use GuzzleHttp\Client;
use Renakdup\CacheWarmUp\Command\CacheWarmupCommand;
use Renakdup\CacheWarmUp\Parser\ParserInterface;
use Renakdup\CacheWarmUp\Parser\Xml;
use Renakdup\SimpleDIC\Container;
use Symfony\Component\Console\Application;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class App
{
    public static function run(): void
    {
        $c = self::getContainerWithDefinitions();
        $command = $c->make(CacheWarmupCommand::class);

        $app = new Application('Cache Warm up', '1.0.0');
        $app->add($command);
        $app->setDefaultCommand($command->getName(), true);
        $app->run();
    }

    private static function getContainerWithDefinitions(): Container
    {
        $c = new Container();
        $c->set(
            ValidatorInterface::class,
            fn() => Validation::createValidatorBuilder()
                ->enableAttributeMapping()
                ->getValidator()
        );
        $c->set(Client::class, fn() => new Client());
        $c->set(
            ParserInterface::class,
            fn(Container $c) => $c->get(Xml::class)
        );
        return $c;
    }
}