<?php

declare(strict_types=1);

namespace App\Domain\Resource\ViewModel;

interface ResourceStorageViewModelInterface
{
    public function getCode(): string;

    public function getAmount(): int;

    public function isFull(): bool;
}
