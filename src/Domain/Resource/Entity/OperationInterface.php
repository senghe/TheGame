<?php

declare(strict_types=1);

namespace App\Domain\Resource\Entity;

interface OperationInterface
{
    public function getId(): int;

    public function isVirtual(): bool;

    public function linkToSnapshot(SnapshotInterface $snapshot): void;

    public function getValue(ResourceInterface $requestedResource): int;
}
