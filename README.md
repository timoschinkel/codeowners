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
    $patterns = (new GitHubParser())->parseFile($filename);
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
    $patterns = (new GitLabParser())->parseString($contents);
    $pattern = (new PatternMatcher(...$patterns))->match($filename);
} catch (\CodeOwners\Exception\UnableToParseException $exception) {
    // unable to read or parse file
} catch (\CodeOwners\Exception\NoMatchFoundException $exception) {
    // no match found
}
```

### Parsers
GitHub, GitLab and BitBucket all have their own features when it comes to code owner files. That's why there are currently two parsers available:
- `GitHubParser`; this parser does strict parsing of owners, and allows entries without owners. This will result in a `Pattern` with an empty owners array.
- `GitLabParser`; this parser does strict parsing of owners, and has support for sections.

NB. I hope to add a parser for BitBucket in the future.

There's also a "plain" `Parser`. This parser does not support any of the specifics and is deliberately unchanged - since the introduction of the vendor specific parsers - to guarantee backwards compatibility. This parser might be deprecated, and thus removed, in the future. The recommendation is to use one of the vendor specific parsers. 

## Known limitations
Currently the library does not handle spaces in file paths.

[github-code-owners]: https://help.github.com/articles/about-codeowners/
[gitlab-code-owners]: https://docs.gitlab.com/ee/user/project/code_owners.html
[bitbucket-code-owners]: https://marketplace.atlassian.com/apps/1218598/code-owners-for-bitbucket-server?hosting=server&tab=overview
[composer]: https://www.getcomposer.org
