<?php

declare(strict_types=1);

namespace App\Component\Resource\Application\Command;

use App\Component\SharedKernel\CommandInterface;

final class ChangeStorageAmountCommand implements CommandInterface
{
    private string $resourceCode;

    private int $value;

    public function __construct(string $resourceCode, int $value)
    {
        $this->resourceCode = $resourceCode;
        $this->value = $value;
    }

    public function getResourceCode(): string
    {
        return $this->resourceCode;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}