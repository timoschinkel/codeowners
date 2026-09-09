<?php

declare(strict_types=1);

namespace CodeOwners\Tests;

use CodeOwners\GitLabParser;
use CodeOwners\ParserInterface;
use CodeOwners\Pattern;
use CodeOwners\Section;
use CodeOwners\SourceInfo;

final class GitLabParserTestCase extends BaseParserTestCase
{
    #[\Override] protected function getParser(): ParserInterface
    {
        return new GitLabParser();
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
                '/feature/strict-owners',
                ['@username', '@org/team-name', 'user@example.com'],
                new SourceInfo($filename, 62),
            ),
            new Pattern(
                '/feature/section-without-owners/with-owner',
                ['@doctocat'],
                new SourceInfo($filename, 67),
                new Section('Section without owners'),
            ),
            new Pattern(
                '/feature/section-without-owners-with-number-of-reviews/with-owner',
                ['@doctocat'],
                new SourceInfo($filename, 71),
                new Section('Section without owners with number of reviews', approvalsRequired: 2),
            ),
            new Pattern(
                '/feature/optional-section-without-owners/with-owner',
                ['@doctocat'],
                new SourceInfo($filename, 75),
                new Section('Optional section without owners', optional: true),
            ),
            new Pattern(
                '/feature/optional-section-without-owners-with-number-of-reviews/with-owner',
                ['@doctocat'],
                new SourceInfo($filename, 79),
                new Section(
                    'Optional section without owners with number of reviews',
                    optional: true,
                    approvalsRequired: 2
                ),
            ),
            new Pattern(
                '/feature/section-with-owners/with-owner',
                ['@doctocat'],
                new SourceInfo($filename, 83),
                new Section('Section with owners', defaultOwners: ['@octocat']),
            ),
            new Pattern(
                '/feature/section-with-owners/without-owner',
                ['@octocat'],
                new SourceInfo($filename, 84),
                new Section('Section with owners', defaultOwners: ['@octocat']),
            ),
            new Pattern(
                '/feature/section-with-owners-with-number-of-reviews/with-owner',
                ['@doctocat'],
                new SourceInfo($filename, 87),
                new Section(
                    'Section with owners with number of reviews',
                    approvalsRequired: 2,
                    defaultOwners: ['@octocat', 'developers@email.com']
                ),
            ),
            new Pattern(
                '/feature/section-with-owners-with-number-of-reviews/without-owner',
                ['@octocat', 'developers@email.com'],
                new SourceInfo($filename, 88),
                new Section(
                    'Section with owners with number of reviews',
                    approvalsRequired: 2,
                    defaultOwners: ['@octocat', 'developers@email.com']
                ),
            ),
            new Pattern(
                '/feature/optional-section-with-owners/with-owner',
                ['@doctocat'],
                new SourceInfo($filename, 91),
                new Section('Optional section with owners', optional: true, defaultOwners: ['@octocat']),
            ),
            new Pattern(
                '/feature/optional-section-with-owners/without-owner',
                ['@octocat'],
                new SourceInfo($filename, 92),
                new Section('Optional section with owners', optional: true, defaultOwners: ['@octocat']),
            ),
            new Pattern(
                '/feature/optional-section-with-owners-with-number-of-reviews/with-owner',
                ['@doctocat'],
                new SourceInfo($filename, 95),
                new Section(
                    'Optional section with owners with number of reviews',
                    optional: true,
                    approvalsRequired: 12,
                    defaultOwners: ['developers@email.com', '@octocat']
                ),
            ),
            new Pattern(
                '/feature/optional-section-with-owners-with-number-of-reviews/without-owner',
                ['developers@email.com', '@octocat'],
                new SourceInfo($filename, 96),
                new Section(
                    'Optional section with owners with number of reviews',
                    optional: true,
                    approvalsRequired: 12,
                    defaultOwners: ['developers@email.com', '@octocat']
                ),
            ),
            new Pattern(
                '/feature/section/with-owner',
                ['@doctocat'],
                new SourceInfo($filename, 99),
                new Section(
                    'Section',
                    defaultOwners: ['@octocat']
                ),
            ),
            new Pattern(
                '/feature/section/without-owner',
                ['@octocat'],
                new SourceInfo($filename, 100),
                new Section(
                    'Section',
                    defaultOwners: ['@octocat']
                ),
            ),
        ];
    }
}
