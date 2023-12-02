<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Command;

use TheGame\Application\SharedKernel\CommandInterface;

final class TargetJourneysCommand implements CommandInterface
{
    public function __construct(
        private readonly string $userId,
    ) {
    }

    public function getUserId(): string
    {
        return $this->userId;
    }
}
