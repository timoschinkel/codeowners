<?php

declare(strict_types=1);

namespace CodeOwners;

final class Section
{
    /**
     * @param string[] $defaultOwners
     */
    public function __construct(
        public readonly string $name,
        public readonly bool $optional = false,
        public readonly ?int $approvalsRequired = null,
        public readonly array $defaultOwners = [],
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isOptional(): bool
    {
        return $this->optional;
    }

    public function getApprovalsRequired(): ?int
    {
        return $this->approvalsRequired;
    }

    public function getDefaultOwners(): array
    {
        return $this->defaultOwners;
    }
}
