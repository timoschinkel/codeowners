<?php

declare(strict_types=1);

namespace CodeOwners;

final class GitHubParser extends BaseParser
{
    public function __construct()
    {
        parent::__construct(new ParserOptions(
            requireStrictOwners: true,
            requireOwners: false,
        ));
    }
}
