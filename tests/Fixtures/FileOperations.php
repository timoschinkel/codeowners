<?php

declare(strict_types=1);

namespace CodeOwners\Tests\Fixtures {

    const NON_EXISTING_FILE = __DIR__ . '/non-existing.codeowners';

    const NON_READABLE_FILE = __DIR__ . '/non-readable.codeowners';

    const NON_OPENABLE_FILE = __DIR__ . '/non-openable.codeowners';

    /**
     * Serves only as a means to autoload this file.
     */
    trait FileOperations
    {
    }

}
namespace CodeOwners {

    use const CodeOwners\Tests\Fixtures\NON_EXISTING_FILE;
    use const CodeOwners\Tests\Fixtures\NON_OPENABLE_FILE;
    use const CodeOwners\Tests\Fixtures\NON_READABLE_FILE;

    function file_exists(string $filename): bool
    {
        if ($filename === NON_READABLE_FILE || $filename === NON_OPENABLE_FILE) {
            return true;
        }

        return $filename === NON_EXISTING_FILE
            ? false
            : \file_exists($filename);
    }

    function is_readable(string $filename): bool
    {
        if ($filename === NON_OPENABLE_FILE) {
            return true;
        }

        return $filename === NON_READABLE_FILE
            ? false
            : \is_readable($filename);
    }


    function fopen(string $filename, string $mode, bool $use_include_path = false, ...$args)
    {
        return $filename === NON_OPENABLE_FILE
            ? false
            : \fopen($filename, $mode, $use_include_path, ...$args);
    }
}
