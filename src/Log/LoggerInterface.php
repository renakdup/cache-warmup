<?php

declare(strict_types=1);

namespace Renakdup\CacheWarmUp\Log;

interface LoggerInterface
{
    public function log(array $data);
}

