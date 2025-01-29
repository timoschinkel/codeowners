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
        return $this->parseIterable($this->getFileIterable($file), $file);
    }

    /**
     * @param string $lines
     * @param ?string $filename
     * @return Pattern[]
     * @throws UnableToParseException
     */
    public function parseString(string $lines, ?string $filename = null): array
    {
        return $this->parseIterable(explode(PHP_EOL, $lines), $filename);
    }

    /**
     * @param iterable<string> $lines
     * @param ?string $filename
     * @return Pattern[]
     */
    private function parseIterable(iterable $lines, ?string $filename = null): array
    {
        $patterns = [];

        foreach ($lines as $index => $line) {
            $line = trim($line);
            $pattern = $this->parseLine($line, new SourceInfo($filename, $index + 1));

            if ($pattern instanceof Pattern) {
                $patterns[] = $pattern;
            }
        }

        return $patterns;
    }

    private function parseLine(string $line, SourceInfo $sourceInfo): ?Pattern
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

        if (preg_match('/^(?P<file_pattern>[^\s]+)\s+(?P<owners>[^#]+)/si', $line, $matches) !== 0) {
            $owners = preg_split('/\s+/', trim($matches['owners']));
            if (!is_array($owners)) {
                // This should not happen as we have full control over the regular expression. In case `preg_split()`
                // fails an E_WARNING will be emitted by `preg_split()`, so we're not doing that twice.
                throw new UnableToParseException('Unable to extract owners from line: ' . $line);
            }
            return new Pattern($matches['file_pattern'], $owners, $sourceInfo);
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
