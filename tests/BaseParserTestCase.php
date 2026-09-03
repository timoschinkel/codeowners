<?php

declare(strict_types=1);

namespace CodeOwners\Tests;

use CodeOwners\Exception\UnableToParseException;
use CodeOwners\ParserInterface;
use CodeOwners\Tests\Fixtures\FileOperations;
use PHPUnit\Framework\TestCase;

use const CodeOwners\Tests\Fixtures\NON_EXISTING_FILE;
use const CodeOwners\Tests\Fixtures\NON_OPENABLE_FILE;
use const CodeOwners\Tests\Fixtures\NON_READABLE_FILE;

abstract class BaseParserTestCase extends TestCase
{
    use FileOperations;

    public function testParsingNonExistingFileThrowsException()
    {
        $this->expectException(UnableToParseException::class);
        $this->expectExceptionMessageMatches('/does not exist/si');
        $this->getParser()->parseFile(NON_EXISTING_FILE);
    }

    public function testParsingNonReadableFileThrowsException()
    {
        $this->expectException(UnableToParseException::class);
        $this->expectExceptionMessageMatches('/is not readable/si');
        $this->getParser()->parseFile(NON_READABLE_FILE);
    }

    public function testParsingNonOpenableFileThrowsException()
    {
        $this->expectException(UnableToParseException::class);
        $this->expectExceptionMessageMatches('/unable to create a reading resource/si');
        $this->getParser()->parseFile(NON_OPENABLE_FILE);
    }

    abstract protected function getParser(): ParserInterface;

    abstract protected function getExpectedPatterns(?string $filename): array;

    public function testParsingResultsInPatterns()
    {
        $filename = __DIR__ . '/Fixtures/CODEOWNERS.example';
        $patterns = $this->getParser()->parseFile($filename);

        $this->assertEquals($this->getExpectedPatterns($filename), $patterns);
    }

    public function testParsingStringResultsInPatterns()
    {
        $patterns = $this->getParser()->parseString(file_get_contents(__DIR__ . '/Fixtures/CODEOWNERS.example'));

        $this->assertEquals($this->getExpectedPatterns(null), $patterns);
    }

    public function testParsingStringWithOptionalFilename()
    {
        $patterns = $this->getParser()->parseString(
            file_get_contents(__DIR__ . '/Fixtures/CODEOWNERS.example'),
            'anonymous'
        );

        $this->assertEquals($this->getExpectedPatterns('anonymous'), $patterns);
    }
}
