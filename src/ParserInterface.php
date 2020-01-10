<?php

declare(strict_types=1);

namespace CodeOwners;

use CodeOwners\Exception\UnableToParseException;

interface ParserInterface
{
    /**
     * @param string $file
     * @return Pattern[]
     * @throws UnableToParseException
     */
    public function parse(string $file): array;
}
