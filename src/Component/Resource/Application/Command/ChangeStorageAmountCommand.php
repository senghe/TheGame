<?php

declare(strict_types=1);

namespace App\Component\Resource\Application\Command;

use App\SharedKernel\CommandInterface;

final class ChangeStorageAmountCommand implements CommandInterface
{
    private string $resourceCode;

    private int $amount;
    private int $planetId;

    public function __construct(
        int $planetId,
        string $resourceCode,
        int $amount
    ) {
        $this->planetId = $planetId;
        $this->resourceCode = $resourceCode;
        $this->amount = $amount;
    }

    public function getPlanetId(): int
    {
        return $this->planetId;
    }

    public function getResourceCode(): string
    {
        return $this->resourceCode;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}