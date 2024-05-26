<?php

declare(strict_types=1);

namespace Renakdup\CacheWarmUp\Console;

use Renakdup\CacheWarmUp\Command\CacheWarmupCommand;

class HydratorConsole
{
    public function hydrate(array $args, array $options, ConsoleDTO $dto): ConsoleDTO
    {
        $dto->url = (string)$args[CacheWarmupCommand::ARG_URL];

        $dto->concurrency = is_numeric($options[CacheWarmupCommand::OPTION_CONCURRENCY])
            ? (int)$options[CacheWarmupCommand::OPTION_CONCURRENCY]
            : $options[CacheWarmupCommand::OPTION_CONCURRENCY];

        $dto->delay = is_numeric($options[CacheWarmupCommand::OPTION_DELAY])
            ? (int)$options[CacheWarmupCommand::OPTION_DELAY]
            : $options[CacheWarmupCommand::OPTION_DELAY];

        $dto->timeout = is_numeric($options[CacheWarmupCommand::OPTION_TIMEOUT])
            ? (float)$options[CacheWarmupCommand::OPTION_TIMEOUT]
            : $options[CacheWarmupCommand::OPTION_TIMEOUT];

        $dto->verbose = $options[CacheWarmupCommand::OPTION_VERBOSE];

        return $dto;
    }
}