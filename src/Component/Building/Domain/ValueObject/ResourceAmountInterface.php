<?php

declare(strict_types=1);

namespace App\Component\Building\Domain\ValueObject;

use App\Component\SharedKernel\Domain\ResourceInterface;

interface ResourceAmountInterface
{
    public function getResource(): ResourceInterface;

    public function getResourceCode(): string;

    public function getAmount(): int;
}