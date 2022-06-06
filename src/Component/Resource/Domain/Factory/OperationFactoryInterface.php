<?php

declare(strict_types=1);

namespace App\Component\Resource\Domain\Factory;

use App\Component\Resource\Domain\Entity\OperationInterface;
use App\Component\Resource\Domain\Entity\ResourceInterface;

interface OperationFactoryInterface
{
    public function createSpeedChange(
        ResourceInterface $resource,
        int $value
    ): OperationInterface;

    public function createChangeAmount(
        ResourceInterface $resource,
        int $value
    ): OperationInterface;
}