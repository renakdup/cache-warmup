<?php

declare(strict_types=1);

namespace Renakdup\CacheWarmUp\Parser;

use Renakdup\CacheWarmUp\Exception\XmlParseException;
use SimpleXMLElement;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Xml implements ParserInterface
{
    public function __construct(private ValidatorInterface $validation)
    {
    }

    /**
     * @throws XmlParseException
     */
    public function parse($data): array
    {
        $doc = simplexml_load_string($data);
        if ($doc === false) {
            throw new XmlParseException("Xml data invalid.");
        }

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

