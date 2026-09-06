<?php

declare(strict_types=1);

namespace CodeOwners\Tests;

use CodeOwners\GitHubParser;
use CodeOwners\ParserInterface;
use CodeOwners\Pattern;
use CodeOwners\SourceInfo;

final class GitHubParserTestCase extends BaseParserTestCase
{
    #[\Override] protected function getParser(): ParserInterface
    {
        return new GitHubParser();
    }

    #[\Override] protected function getExpectedPatterns(?string $filename): array
    {
        return [
            new Pattern(
                '*',
                ['@global-owner1', '@global-owner2'],
                new SourceInfo($filename, 10)
            ),
            new Pattern(
                '*.js',
                ['@js-owner'],
                new SourceInfo($filename, 16)
            ),
            new Pattern(
                '*.go',
                ['docs@example.com'],
                new SourceInfo($filename, 21)
            ),
            new Pattern(
                '/build/logs/',
                ['@doctocat'],
                new SourceInfo($filename, 26)
            ),
            new Pattern(
                'docs/*',
                ['docs@example.com'],
                new SourceInfo($filename, 31)
            ),
            new Pattern(
                'apps/',
                ['@octocat'],
                new SourceInfo($filename, 35)
            ),
            new Pattern(
                '/docs/',
                ['@doctocat'],
                new SourceInfo($filename, 39)
            ),
            new Pattern(
                '**/foo',
                ['@doctocat'],
                new SourceInfo($filename, 45)
            ),
            new Pattern(
                'abc/**',
                ['@doctocat'],
                new SourceInfo($filename, 50)
            ),
            new Pattern(
                'a/**/b',
                ['@doctocat'],
                new SourceInfo($filename, 55)
            ),
            new Pattern(
                '/src',
                [],
                new SourceInfo($filename, 59)
            ),
            new Pattern(
                '/feature/strict-owners',
                ['@username', '@org/team-name', 'user@example.com'],
                new SourceInfo($filename, 62),
            ),
        ];
    }
}
