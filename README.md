# Code owners

Code Owners allows you to parse [code owners files][github-code-owners] and apply the outcome to all kinds of different result sets ranging from code coverage to static analysis results.

Code Owners files are supported by Github, [Gitlab][gitlab-code-owners] and [Bitbucket][bitbucket-code-owners].

## Installation

Use [Composer][composer] for installation:

```bash
composer require timoschinkel/codeowners
```

## Usage

Parse `CODEOWNERS` file:

```php
<?php

use CodeOwners\Parser;
use CodeOwners\PatternMatcher;

try {
    $patterns = (new Parser())->parseFile($filename);
    $pattern = (new PatternMatcher(...$patterns))->match($filename);
} catch (\CodeOwners\Exception\UnableToParseException $exception) {
    // unable to read or parse file
} catch (\CodeOwners\Exception\NoMatchFoundException $exception) {
    // no match found
}
```

Alternatively, parsing a string directly is also supported:

```php
<?php

use CodeOwners\Parser;
use CodeOwners\PatternMatcher;

try {
    $patterns = (new Parser())->parseString($contents);
    $pattern = (new PatternMatcher(...$patterns))->match($filename);
} catch (\CodeOwners\Exception\UnableToParseException $exception) {
    // unable to read or parse file
} catch (\CodeOwners\Exception\NoMatchFoundException $exception) {
    // no match found
}
```

## Known limitations
Currently the library does not handle spaces in file paths.

[github-code-owners]: https://help.github.com/articles/about-codeowners/
[gitlab-code-owners]: https://docs.gitlab.com/ee/user/project/code_owners.html
[bitbucket-code-owners]: https://marketplace.atlassian.com/apps/1218598/code-owners-for-bitbucket-server?hosting=server&tab=overview
[composer]: https://www.getcomposer.org
