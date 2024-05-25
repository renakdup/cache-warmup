<?php

declare(strict_types=1);

namespace Renakdup\CacheWarmUp\Console;

use Symfony\Component\Validator\Constraints as Assert;

class ConsoleDTO
{
    #[Assert\Type('string')]
    #[Assert\Url]
    #[Assert\NotNull]
    public $url;

    #[Assert\Type('integer')]
    #[Assert\GreaterThanOrEqual(1)]
    #[Assert\NotNull]
    public $concurrency;

    #[Assert\Type('integer')]
    #[Assert\GreaterThanOrEqual(0)]
    #[Assert\NotNull]
    public $delay;

    #[Assert\Type('integer')]
    #[Assert\GreaterThanOrEqual(0)]
    #[Assert\NotNull]
    public $timeout;
}