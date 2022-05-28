<?php

declare(strict_types=1);

namespace App\Component\Resource\Domain\Entity;

interface SnapshotInterface
{
    public function getId(): int;

    public function hasEnough(ResourceInterface $resource, int $amount): bool;

    public function addStorage(StorageInterface $storage): void;

    public function performOperation(OperationInterface $operation): void;

    public function linkPrevious(SnapshotInterface $previous): void;
}
