<?php

declare(strict_types=1);

namespace Renakdup\CacheWarmUp\Parser;

interface ParserInterface
{
    public function parse($data): array;
}

