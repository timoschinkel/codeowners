<?php

declare(strict_types=1);

namespace CodeOwners\Tests;

use CodeOwners\Exception\UnableToParseException;
use CodeOwners\Parser;
use CodeOwners\Pattern;
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
        self::expectException(UnableToParseException::class);
        self::expectExceptionMessageRegExp('/does not exist/si');
        (new Parser())->parse(NON_EXISTING_FILE);
    }

    public function testParsingNonReadableFileThrowsException()
    {
        self::expectException(UnableToParseException::class);
        self::expectExceptionMessageRegExp('/is not readable/si');
        (new Parser())->parse(NON_READABLE_FILE);
    }

    public function testParsingNonOpenableFileThrowsException()
    {
        self::expectException(UnableToParseException::class);
        self::expectExceptionMessageRegExp('/unable to create a reading resource/si');
        (new Parser())->parse(NON_OPENABLE_FILE);
    }

    public function testParsingResultsInPatterns()
    {
        $patterns = (new Parser())->parse(__DIR__ . '/Fixtures/CODEOWNERS.example');

        self::assertEquals([
            new Pattern('*', ['@global-owner1', '@global-owner2']),
            new Pattern('*.js', ['@js-owner']),
            new Pattern('*.go', ['docs@example.com']),
            new Pattern('/build/logs/', ['@doctocat']),
            new Pattern('docs/*', ['docs@example.com']),
            new Pattern('apps/', ['@octocat']),
            new Pattern('/docs/', ['@doctocat']),
        ], $patterns);
    }
}
