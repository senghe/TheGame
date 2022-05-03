<?php

declare(strict_types=1);

namespace App\Resource\Domain;

interface ResourceStorageViewModelInterface
{
    public function getCode(): string;

    public function getAmount(): int;

    public function isFull(): bool;
}
