<?php

declare(strict_types=1);

namespace App\Component\Building\Domain\ValueObject;

use App\SharedKernel\Domain\Entity\ResourceInterface;

interface ResourceAmountInterface
{
    public function getResource(): ResourceInterface;

    public function getResourceCode(): string;

    public function getAmount(): int;
}