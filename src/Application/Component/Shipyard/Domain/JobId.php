<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\Domain;

final class JobId implements JobIdInterface
{
    public function __construct(
        private readonly string $id,
    ) {
    }

    public function getUuid(): string
    {
        return $this->id;
    }
}
