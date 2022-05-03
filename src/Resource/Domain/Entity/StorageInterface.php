<?php

declare(strict_types=1);

namespace App\Resource\Domain\Entity;

use App\Resource\Domain\ResourceStorageViewModelInterface;

interface StorageInterface
{
    public function getId(): int;

    public function getCurrentAmount(): int;

    public function performOperation(OperationInterface $operation): void;

    public function toViewModel(): ResourceStorageViewModelInterface;

    public function cloneFor(SnapshotInterface $snapshot): StorageInterface;
}
