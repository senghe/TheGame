<?php

declare(strict_types=1);

namespace App\Component\Resource\Domain;

use App\Component\Resource\Domain\Entity\OperationInterface;
use App\Component\Resource\Domain\Enum\OperationType;
use App\SharedKernel\AggregateInterface as BaseAggregateRootInterface;

interface AggregateInterface extends BaseAggregateRootInterface
{
    public function performOperation(OperationInterface $operation): void;

    public function removeOperationsNotPerformedYet(OperationType $operationType): void;
}
