<?php

declare(strict_types=1);

namespace App\Domain\Resource\ViewModel;

final class ResourceStorageViewModel implements ResourceStorageViewModelInterface
{
    private string $code;

    private int $amount;

    private bool $isFull;

    public function __construct(
        string $code,
        int $amount,
        bool $isFull
    ) {
        $this->code = $code;
        $this->amount = $amount;
        $this->isFull = $isFull;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function isFull(): bool
    {
        return $this->isFull;
    }
}
