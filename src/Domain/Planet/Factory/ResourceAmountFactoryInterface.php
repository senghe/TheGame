<?php

declare(strict_types=1);

namespace App\Domain\Planet\Factory;

use App\Domain\Planet\ValueObject\ResourceAmountInterface;
use App\Domain\SharedKernel\ResourceInterface;

interface ResourceAmountFactoryInterface
{
    public function create(
        ResourceInterface $resource,
        int $amount
    ): ResourceAmountInterface;
}