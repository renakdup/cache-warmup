<?php

declare(strict_types=1);

namespace Renakdup\CacheWarmUp\Parser;

use SimpleXMLElement;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Xml implements ParserInterface
{
    public function __construct(private ValidatorInterface $validation)
    {
    }

    public function parse($data): array
    {
        $doc = new SimpleXMLElement($data);
        $urlConstraint = new Assert\Url();

        $urls = [];
        foreach ($doc as $item) {
            if (!isset($item->loc)) {
                continue;
            }

            $url = (string)$item->loc ?? '';

            if (count($this->validation->validate($url, $urlConstraint)) > 0) {
                continue;
            }

            $urls[] = $url;
        }

        return $urls;
    }
}

