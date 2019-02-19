<?php
declare(strict_types=1);

namespace CodeOwners;

use CodeOwners\Exception\NoMatchFoundException;

final class PatternMatcher implements PatternMatcherInterface
{
    /** @var Pattern[] */
    private $patterns;

    public function __construct(Pattern ...$patterns)
    {
        $this->patterns = $patterns;
    }

    public function match(string $filename): Pattern
    {
        $matchedPatterns = array_filter(
            $this->patterns,
            function(Pattern $pattern) use ($filename): bool
            {
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
            -1,
            PREG_SPLIT_DELIM_CAPTURE
        );

        $regex = join(array_map(function(string $part) use ($delimiter): string{
            $replacements = [
                '*' => '[^\/]+',
                '?' => '[^\/]',
                '**' => '.*',
                '/**/' => '\/([^\/]+\/)*',
            ];

            return $replacements[$part] ?? preg_quote($part, $delimiter);
        }, $parts));

        if (mb_substr($regex, 0, 1) === '/') {
            $regex = '^' . mb_substr($regex, 1);
        } else {
            $regex = '^(.+\/)?' . $regex;
        }

        if (mb_substr($regex, -1, 1) === '/') {
            $regex = mb_substr($regex, 0, -1) . '\/.+$';
        } elseif (mb_substr($pattern->getPattern(), -1, 1) === '*' && mb_substr($pattern->getPattern(), -2, 1) !== '*') {
            $regex = $regex . '$';
        } else {
            $regex = $regex . '(\/.+)?$';
        }

//        die(var_dump(__METHOD__, __LINE__, mb_substr($pattern->getPattern(), -1, 1), mb_substr($pattern->getPattern(), -2, 1)));

//        die(var_dump(__METHOD__, __LINE__, $regex, $filename));

        return preg_match($delimiter . $regex . $delimiter . 's', $filename) === 1;
    }
}
