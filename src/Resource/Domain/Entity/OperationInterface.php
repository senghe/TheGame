<?php

declare(strict_types=1);

namespace App\Resource\Domain\Entity;

interface OperationInterface
{
    public function getId(): int;

    public function linkToSnapshot(SnapshotInterface $snapshot): void;

    public function getValue(ResourceInterface $requestedResource): int;
}
