<?php

declare(strict_types=1);

namespace CodeOwners;

use CodeOwners\Exception\UnableToParseException;

final class Parser implements ParserInterface
{
    /**
     * @param string $file
     * @return Pattern[]
     * @throws UnableToParseException
     */
    public function parseFile(string $file): array
    {
        return $this->parseIterable($this->getFileIterable($file));
    }

    /**
     * @param string $lines
     * @return Pattern[]
     * @throws UnableToParseException
     */
    public function parseString(string $lines): array
    {
        return $this->parseIterable(explode(PHP_EOL, $lines));
    }

    /**
     * @param iterable<string> $lines
     * @return Pattern[]
     */
    private function parseIterable(iterable $lines): array
    {
        $patterns = [];

        foreach ($lines as $line) {
            $line = trim($line);
            $pattern = $this->parseLine($line);

            if ($pattern instanceof Pattern) {
                $patterns[] = $pattern;
            }
        }

        return $patterns;
    }

    private function parseLine(string $line): ?Pattern
    {
        $line = trim($line);

        if (substr($line, 0, 1) === '#') {
            // comment
            return null;
        }

        if ($line === '') {
            // empty line
            return null;
        }

        if (preg_match('/^(?P<file_pattern>[^\s]+)\s+(?P<owners>.+)$/si', $line, $matches) !== 0) {
            $owners = preg_split('/\s+/', $matches['owners']);
            return new Pattern($matches['file_pattern'], $owners);
        }

        return null;
    }

    /**
     * @param string $file
     * @return iterable<string>
     */
    private function getFileIterable(string $file): iterable
    {
        if (file_exists($file) === false) {
            throw new UnableToParseException("File {$file} does not exist");
        }

        if (is_readable($file) === false) {
            throw new UnableToParseException("File {$file} is not readable");
        }

        $handle = fopen($file, 'rb');
        if (is_resource($handle) === false) {
            throw new UnableToParseException("Unable to create a reading resource for {$file}");
        }

        while ($line = fgets($handle)) {
            yield $line;
        }
        fclose($handle);
    }
}
