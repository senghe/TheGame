<?php

declare(strict_types=1);

namespace App\Component\Resource\Domain\Entity;

use DateTimeInterface;

interface StorageInterface
{
    public const MAX_OPERATIONS_COUNT = 10;

    public function getId(): int;

    public function isFor(ResourceInterface $resource): bool;

    public function getAmount(): int;

    public function accept(OperationInterface $operation): void;

    public function isClosed(): bool;

    public function cloneFor(SnapshotInterface $snapshot): StorageInterface;

    public function lock(): void;

    public function removeOperationsOverTime(
        $operationType,
        DateTimeInterface $time
    ): void;
}
