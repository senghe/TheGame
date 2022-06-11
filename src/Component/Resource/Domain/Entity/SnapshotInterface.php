<?php

declare(strict_types=1);

namespace App\Component\Resource\Domain\Entity;

use App\SharedKernel\EntityInterface;
use DateTimeInterface;

interface SnapshotInterface extends EntityInterface
{
    public function hasEnough(ResourceInterface $resource, int $amount): bool;

    public function addStorage(StorageInterface $storage): void;

    public function performOperation(OperationInterface $operation): void;

    public function linkPrevious(SnapshotInterface $previous): void;

    public function removeOperationsOverTime(
        $operationType,
        DateTimeInterface $time
    ): void;
}
