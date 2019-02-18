<?php
declare(strict_types=1);

namespace CodeOwners;

final class Pattern
{
    /** @var string */
    private $pattern;

    /** @var string[] */
    private $owners;

    public function __construct(string $pattern, array $owners)
    {
        $this->pattern = $pattern;
        $this->owners = $owners;
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }

    public function getOwners(): array
    {
        return $this->owners;
    }
}
