<?php

declare(strict_types=1);

namespace App\Component\Resource\Application\Command;

use App\SharedKernel\CommandInterface;

final class DecreaseMiningSpeedCommand implements CommandInterface
{
    private int $planetId;

    public function __construct(
        int $planetId
    ) {
        $this->planetId = $planetId;
    }

    public function getPlanetId(): int
    {
        return $this->planetId;
    }
}