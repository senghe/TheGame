<?php

declare(strict_types=1);

namespace App\Domain\Planet\ValueObject;

use App\Domain\SharedKernel\ResourceInterface;

interface ResourceAmountInterface
{
    public function getResource(): ResourceInterface;

    public function getResourceCode(): string;

    public function getAmount(): int;
}