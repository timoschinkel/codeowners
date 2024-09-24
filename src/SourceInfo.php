<?php

namespace CodeOwners;

final class SourceInfo
{
    private string $filename;

    private int $line;

    public function __construct(
        string $filename,
        int $line
    ) {
        $this->filename = $filename;
        $this->line = $line;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getLine(): int
    {
        return $this->line;
    }
}