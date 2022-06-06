<?php

declare(strict_types=1);

namespace App\Component\Resource\Application\Command;

use App\SharedKernel\CommandInterface;

final class IncreaseMiningSpeedCommand implements CommandInterface
{
    private int $planetId;

    private string $resourceCode;

    private int $speed;

    public function __construct(
        int $planetId,
        string $resourceCode,
        int $speed
    ) {
        $this->planetId = $planetId;
        $this->resourceCode = $resourceCode;
        $this->speed = $speed;
    }

    public function getPlanetId(): int
    {
        return $this->planetId;
    }

    public function getResourceCode(): string
    {
        return $this->resourceCode;
    }

    public function getSpeed(): int
    {
        return $this->speed;
    }
}