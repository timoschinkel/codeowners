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
        $patterns = (new Parser())->parseFile(__DIR__ . '/Fixtures/CODEOWNERS.example');

        $this->assertEquals([
            new Pattern('*', ['@global-owner1', '@global-owner2']),
            new Pattern('*.js', ['@js-owner']),
            new Pattern('*.go', ['docs@example.com']),
            new Pattern('/build/logs/', ['@doctocat']),
            new Pattern('docs/*', ['docs@example.com']),
            new Pattern('apps/', ['@octocat']),
            new Pattern('/docs/', ['@doctocat']),
        ], $patterns);
    }

    public function testParsingStringResultsInPatterns()
    {
        $patterns = (new Parser())->parseString(file_get_contents(__DIR__ . '/Fixtures/CODEOWNERS.example'));

        $this->assertEquals([
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
