<?php

declare(strict_types=1);

namespace CodeOwners;

use CodeOwners\Exception\NoMatchFoundException;

final class PatternMatcher implements PatternMatcherInterface
{
    /** @var Pattern[] */
    private $patterns;

    /** @var int */
    private const NO_LIMIT = -1;

    public function __construct(Pattern ...$patterns)
    {
        $this->patterns = $patterns;
    }

    public function match(string $filename): Pattern
    {
        $matchedPatterns = array_filter(
            $this->patterns,
            function (Pattern $pattern) use ($filename): bool {
                return $this->isMatch($pattern, $filename);
            }
        );

        if (count($matchedPatterns) === 0) {
            throw new NoMatchFoundException("Unable to find a pattern to match ${filename}");
        }

        return end($matchedPatterns);
    }

    private function isMatch(Pattern $pattern, string $filename): bool
    {
        // This method converts the pattern to a regular expression using the following guidelines:
        //
        // *            => [^/]+
        // ?            => [^/]
        // [<range>]    => [<range>]
        // **           => .*
        // /<pattern>   => ^<pattern>
        // <pattern>/   => <pattern>/.+$
        // <pattern>/*  => <pattern>/[^/]+$

        // construct regular expression
        $delimiter = '#';

        $parts = preg_split(
            '/(\/?\*\*\/?)|(\*)|\?|(\[.+?\])/i',
            $pattern->getPattern(),
            self::NO_LIMIT,
            PREG_SPLIT_DELIM_CAPTURE
        );

        $regex = join(array_map(function (string $part) use ($delimiter): string {
            $replacements = [
                '*' => '[^\/]+',
                '?' => '[^\/]',
                '**' => '.*',
                '/**' => '\/.*',
                '**/' => '.*\/',
                '/**/' => '\/([^\/]+\/)*',
            ];

            return $replacements[$part] ?? preg_quote($part, $delimiter);
        }, $parts));

        // check whether the regex starts with `/`
        if (substr($regex, 0, 1) === '/') {
            $regex = '^' . substr($regex, 1);
        } else {
            $regex = '^(.+\/)?' . $regex;
        }

        // check whether the pattern ends with a `/`
        if (substr($regex, -1) === '/') {
            $regex = substr($regex, 0, -1) . '\/.+$';
            // or whether the pattern ends with `*`, but not with `**`
        } elseif (preg_match('/[^\*]+\*$/s', $pattern->getPattern()) === 1) {
            $regex = $regex . '$';
        } else {
            $regex = $regex . '(\/.+)?$';
        }

        return preg_match($delimiter . $regex . $delimiter . 's', $filename) === 1;
    }
}
