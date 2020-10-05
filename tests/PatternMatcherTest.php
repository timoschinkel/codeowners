<?php

declare(strict_types=1);

namespace CodeOwners\Tests;

use CodeOwners\Exception\NoMatchFoundException;
use CodeOwners\Pattern;
use CodeOwners\PatternMatcher;
use PHPUnit\Framework\TestCase;

class PatternMatcherTest extends TestCase
{
    public function testNoMatchesWillThrowException()
    {
        $this->expectException(NoMatchFoundException::class);
        (new PatternMatcher())->match('non-existing.file');
    }

    /**
     * @dataProvider provideCorrectMatchIsReturnedForFilename
     * @param Pattern $pattern
     * @param string $filename
     */
    public function testCorrectMatchIsReturnedForFilename(Pattern $pattern, string $filename): void
    {
        $this->assertEquals(
            $pattern,
            (new PatternMatcher($pattern))->match($filename)
        );
    }

    public function provideCorrectMatchIsReturnedForFilename(): array
    {
        return [
            [new Pattern('foo', ['@owner']), 'foo'],
            [new Pattern('foo', ['@owner']), 'foo/file.ext'],
            [new Pattern('foo', ['@owner']), 'foo/bar/file.ext'],

            // trailing slash
            [new Pattern('foo/', ['@owner']), 'foo/file.ext'],
            [new Pattern('foo/', ['@owner']), 'foo/bar/file.ext'],
            [new Pattern('foo/', ['@owner']), 'bar/foo/file.ext'],
            // does NOT match "foo" or "bar/foo"

            // leading slash
            [new Pattern('/foo', ['@owner']), 'foo'],
            [new Pattern('/foo', ['@owner']), 'foo/bar'],
            // does NOT match "foobar" or "bar/foo"

            // leading and trailing slash
            [new Pattern('/foo/', ['@owner']), 'foo/file.ext'],
            [new Pattern('/foo/', ['@owner']), 'foo/bar/file.ext'],
            // does NOT match "foo"
            [new Pattern('/foo/bar/', ['@owner']), 'foo/bar/file.ext'],

            // *
            [new Pattern('*', ['@owner']), 'file.ext'],
            [new Pattern('*', ['@owner']), 'foo/file.ext'],
            [new Pattern('*.ext', ['@owner']), 'file.ext'],
            [new Pattern('*.ext', ['@owner']), 'foo/file.ext'],
            // does NOT match "foo/ext
            [new Pattern('foo/*', ['@owner']), 'foo/file.ext'],
            [new Pattern('foo/*', ['@owner']), 'bar/foo/file.ext'],
            [new Pattern('foo/*/*', ['@owner']), 'bar/foo/biz/file.ext'],
            // does NOT match "foo/bar/file.ext"

            // **
            [new Pattern('a/**/b', ['@owner']), 'a/b'],
            [new Pattern('a/**/b', ['@owner']), 'a/x/b'],
            [new Pattern('a/**/b', ['@owner']), 'a/x/y/b'],
            [new Pattern('a/**/b/**/c', ['@owner']), 'a/x/y/b/x/y/c'],
        ];
    }

    public function testLastMatchGetsOwnership(): void
    {
        $matcher = new PatternMatcher(
            new Pattern('*', ['@owner-of-all']),
            new Pattern('*.php', ['@owner-of-all-php']),
            new Pattern('src/foo/bar/', ['@owner-of-foo-bar'])
        );

        $match = $matcher->match('src/foo/bar/MyClass.php');

        $this->assertEquals(['@owner-of-foo-bar'], $match->getOwners());
    }

    /**
     * @param Pattern $pattern
     * @param string $filename
     * @dataProvider provideNoMatchFoundExceptionIsThrownForFilename
     */
    public function testNoMatchFoundExceptionIsThrownForFilename(Pattern $pattern, string $filename): void
    {
        try {
            (new PatternMatcher($pattern))->match($filename);

            $this->assertTrue(false, 'Pattern "' . $pattern->getPattern() . '" matches "' . $filename . '"');
        } catch (NoMatchFoundException $exception) {
            $this->assertTrue(true);
        }
    }

    public function provideNoMatchFoundExceptionIsThrownForFilename(): array
    {
        return [
            [new Pattern('foo', ['@owner']), 'foo.ext'],
            [new Pattern('foo', ['@owner']), 'foobar'],
            [new Pattern('foo', ['@owner']), 'barfoo'],

            // trailing slash
            [new Pattern('foo/', ['@owner']), 'foo'],
            [new Pattern('foo/', ['@owner']), 'bar/foo'],

            // leading slash
            [new Pattern('/foo', ['@owner']), 'foobar'],
            [new Pattern('/foo', ['@owner']), 'bar/foo'],

            // leading and trailing slash
            [new Pattern('/foo/', ['@owner']), 'foo'],
            [new Pattern('/foo/', ['@owner']), 'bar/foo/biz'],

            // *
            [new Pattern('*.ext', ['@owner']), 'foo/fileext'],
            [new Pattern('*.ext', ['@owner']), 'fooext'],
            [new Pattern('*.ext', ['@owner']), 'foo/ext'],
            [new Pattern('foo/*', ['@owner']), 'foo/bar/file.ext'],
        ];
    }
}
