<?php

declare(strict_types=1);

namespace App\Component\Resource\Application\Command;

use App\Component\SharedKernel\CommandInterface;

final class IncreaseMiningSpeedCommand implements CommandInterface
{
    private string $resourceCode;

    private int $speed;

    public function __construct(
        string $resourceCode,
        int $speed
    ) {
        $this->resourceCode = $resourceCode;
        $this->speed = $speed;
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