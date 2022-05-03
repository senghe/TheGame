<?php

declare(strict_types=1);

namespace App\Resource\Domain\Entity;

use Doctrine\Common\Collections\Collection;

interface SnapshotInterface
{
    public function getId(): int;

    public function addStorage(StorageInterface $storage): void;

    public function performOperation(OperationInterface $operation): void;

    public function isClosed(): bool;

    public function linkPrevious(SnapshotInterface $previous): void;

    public function getResourcesViewModel(): Collection;
}
