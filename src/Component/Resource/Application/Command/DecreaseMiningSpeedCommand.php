<?php

declare(strict_types=1);

namespace App\Component\Resource\Application\Command;

use App\Component\SharedKernel\CommandInterface;

final class DecreaseMiningSpeedCommand implements CommandInterface
{
    private string $buildingCode;

    public function __construct(string $buildingCode)
    {
        $this->buildingCode = $buildingCode;
    }

    public function getBuildingCode(): string
    {
        return $this->buildingCode;
    }
}