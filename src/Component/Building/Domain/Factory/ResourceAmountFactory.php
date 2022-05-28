<?php

declare(strict_types=1);

namespace App\Component\Building\Domain\Factory;

use App\Component\Building\Domain\ValueObject\ResourceAmount;
use App\Component\Building\Domain\ValueObject\ResourceAmountInterface;
use App\Component\SharedKernel\Domain\ResourceInterface;

final class ResourceAmountFactory implements ResourceAmountFactoryInterface
{
    public function create(
        ResourceInterface $resource,
        int $amount
    ): ResourceAmountInterface {
        return new ResourceAmount($resource, $amount);
    }
}