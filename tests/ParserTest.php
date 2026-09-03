<?php

declare(strict_types=1);

namespace CodeOwners\Tests;

use CodeOwners\Exception\UnableToParseException;
use CodeOwners\Parser;
use CodeOwners\Pattern;
use CodeOwners\SourceInfo;
use CodeOwners\Tests\Fixtures\FileOperations;
use PHPUnit\Framework\TestCase;

use const CodeOwners\Tests\Fixtures\NON_EXISTING_FILE;
use const CodeOwners\Tests\Fixtures\NON_OPENABLE_FILE;
use const CodeOwners\Tests\Fixtures\NON_READABLE_FILE;

class ParserTest extends TestCase
{
    use FileOperations;

    public function testParsingNonExistingFileThrowsException()
    {
        $this->expectException(UnableToParseException::class);
        $this->expectExceptionMessageMatches('/does not exist/si');
        (new Parser())->parseFile(NON_EXISTING_FILE);
    }

    public function testParsingNonReadableFileThrowsException()
    {
        $this->expectException(UnableToParseException::class);
        $this->expectExceptionMessageMatches('/is not readable/si');
        (new Parser())->parseFile(NON_READABLE_FILE);
    }

    public function testParsingNonOpenableFileThrowsException()
    {
        $this->expectException(UnableToParseException::class);
        $this->expectExceptionMessageMatches('/unable to create a reading resource/si');
        (new Parser())->parseFile(NON_OPENABLE_FILE);
    }

    public function testParsingResultsInPatterns()
    {
        $filename = __DIR__ . '/Fixtures/CODEOWNERS.example';
        $patterns = (new Parser())->parseFile($filename);

        $this->assertEquals([
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
                '**/with spaces',
                ['@doctocat'],
                new SourceInfo($filename, 63)
            ),
            new Pattern(
                '/owners/username',
                ['@username'],
                new SourceInfo($filename, 67)
            ),
            new Pattern(
                '/owners/team-name',
                ['@org/team-name'],
                new SourceInfo($filename, 68)
            ),
            new Pattern(
                '/owners/email',
                ['user@example.com'],
                new SourceInfo($filename, 69)
            ),
            new Pattern(
                '/owners/role',
                ['@@maintainer'],
                new SourceInfo($filename, 70)
            ),
        ], $patterns);
    }

    public function testParsingStringResultsInPatterns()
    {
        $patterns = (new Parser())->parseString(file_get_contents(__DIR__ . '/Fixtures/CODEOWNERS.example'));

        $this->assertEquals([
            new Pattern(
                '*',
                ['@global-owner1', '@global-owner2'],
                new SourceInfo('', 10)
            ),
            new Pattern(
                '*.js',
                ['@js-owner'],
                new SourceInfo('', 16)
            ),
            new Pattern(
                '*.go',
                ['docs@example.com'],
                new SourceInfo('', 21)
            ),
            new Pattern(
                '/build/logs/',
                ['@doctocat'],
                new SourceInfo('', 26)
            ),
            new Pattern(
                'docs/*',
                ['docs@example.com'],
                new SourceInfo(null, 31)
            ),
            new Pattern(
                'apps/',
                ['@octocat'],
                new SourceInfo(null, 35)
            ),
            new Pattern(
                '/docs/',
                ['@doctocat'],
                new SourceInfo(null, 39)
            ),
            new Pattern(
                '**/foo',
                ['@doctocat'],
                new SourceInfo(null, 45)
            ),
            new Pattern(
                'abc/**',
                ['@doctocat'],
                new SourceInfo(null, 50)
            ),
            new Pattern(
                'a/**/b',
                ['@doctocat'],
                new SourceInfo(null, 55)
            ),
            new Pattern(
                '**/with spaces',
                ['@doctocat'],
                new SourceInfo(null, 63)
            ),
            new Pattern(
                '/owners/username',
                ['@username'],
                new SourceInfo(null, 67)
            ),
            new Pattern(
                '/owners/team-name',
                ['@org/team-name'],
                new SourceInfo(null, 68)
            ),
            new Pattern(
                '/owners/email',
                ['user@example.com'],
                new SourceInfo(null, 69)
            ),
            new Pattern(
                '/owners/role',
                ['@@maintainer'],
                new SourceInfo(null, 70)
            ),
        ], $patterns);
    }

    public function testParsingStringWithOptionalFilename()
    {
        $patterns = (new Parser())->parseString(
            file_get_contents(__DIR__ . '/Fixtures/CODEOWNERS.example'),
            'anonymous'
        );

        $this->assertEquals([
            new Pattern(
                '*',
                ['@global-owner1', '@global-owner2'],
                new SourceInfo('anonymous', 10)
            ),
            new Pattern(
                '*.js',
                ['@js-owner'],
                new SourceInfo('anonymous', 16)
            ),
            new Pattern(
                '*.go',
                ['docs@example.com'],
                new SourceInfo('anonymous', 21)
            ),
            new Pattern(
                '/build/logs/',
                ['@doctocat'],
                new SourceInfo('anonymous', 26)
            ),
            new Pattern(
                'docs/*',
                ['docs@example.com'],
                new SourceInfo('anonymous', 31)
            ),
            new Pattern(
                'apps/',
                ['@octocat'],
                new SourceInfo('anonymous', 35)
            ),
            new Pattern(
                '/docs/',
                ['@doctocat'],
                new SourceInfo('anonymous', 39)
            ),
            new Pattern(
                '**/foo',
                ['@doctocat'],
                new SourceInfo('anonymous', 45)
            ),
            new Pattern(
                'abc/**',
                ['@doctocat'],
                new SourceInfo('anonymous', 50)
            ),
            new Pattern(
                'a/**/b',
                ['@doctocat'],
                new SourceInfo('anonymous', 55)
            ),
            new Pattern(
                '**/with spaces',
                ['@doctocat'],
                new SourceInfo('anonymous', 63)
            ),
            new Pattern(
                '/owners/username',
                ['@username'],
                new SourceInfo('anonymous', 67)
            ),
            new Pattern(
                '/owners/team-name',
                ['@org/team-name'],
                new SourceInfo('anonymous', 68)
            ),
            new Pattern(
                '/owners/email',
                ['user@example.com'],
                new SourceInfo('anonymous', 69)
            ),
            new Pattern(
                '/owners/role',
                ['@@maintainer'],
                new SourceInfo('anonymous', 70)
            ),
        ], $patterns);
    }
}
