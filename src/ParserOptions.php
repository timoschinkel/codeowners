<?php

declare(strict_types=1);

namespace CodeOwners;

final class ParserOptions
{
    public function __construct(
        public readonly bool $suppressUnableToParseException = false,
        public readonly bool $requireStrictOwners = false,
        public readonly bool $requireOwners = true,
        public readonly bool $supportSectionHeaders = false,
    ) {
    }
}
