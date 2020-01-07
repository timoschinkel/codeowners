<?php

declare(strict_types=1);

namespace CodeOwners;

use CodeOwners\Exception\NoMatchFoundException;

interface PatternMatcherInterface
{
    /**
     * Attempts to match the `$filename` against available patterns.
     *
     * @param string $filename The path and filename relative to the project root
     * @return Pattern
     * @throws NoMatchFoundException If no match is found for `$filename`
     */
    public function match(string $filename): Pattern;
}
