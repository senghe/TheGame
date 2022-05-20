<?php

declare(strict_types=1);

namespace App\Domain\Resource\Entity;

use App\Domain\Resource\ViewModel\ResourceStorageViewModelInterface;

interface StorageInterface
{
    public function getId(): int;

    public function isFor(ResourceInterface $resource): bool;

    public function getAmount(): int;

    public function accept(OperationInterface $operation): void;

    public function toViewModel(): ResourceStorageViewModelInterface;

    public function cloneFor(SnapshotInterface $snapshot): StorageInterface;
}
