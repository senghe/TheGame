<?php

declare(strict_types=1);

namespace App\Component\Building\Domain\Factory;

use App\Component\Building\Domain\ValueObject\ResourceAmountInterface;
use App\Component\SharedKernel\Domain\ResourceInterface;

interface ResourceAmountFactoryInterface
{
    public function create(
        ResourceInterface $resource,
        int $amount
    ): ResourceAmountInterface;
}