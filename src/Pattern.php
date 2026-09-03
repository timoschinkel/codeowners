<?php

declare(strict_types=1);

namespace CodeOwners;

final class Pattern
{
    /** @var string */
    private $pattern;

    /** @var string[] */
    private $owners;

    private ?SourceInfo $sourceInfo;

    private ?Section $section;

    public function __construct(string $pattern, array $owners, ?SourceInfo $sourceInfo = null, ?Section $section = null)
    {
        $this->pattern = $pattern;
        $this->owners = $owners;
        $this->sourceInfo = $sourceInfo;
        $this->section = $section;
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }

    public function getOwners(): array
    {
        return $this->owners;
    }

    public function getSourceInfo(): ?SourceInfo
    {
        return $this->sourceInfo;
    }

    public function getSection(): ?Section
    {
        return $this->section;
    }
}
