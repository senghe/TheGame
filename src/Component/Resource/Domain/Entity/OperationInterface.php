<?php

declare(strict_types=1);

namespace App\Component\Resource\Domain\Entity;

use App\Component\Resource\Domain\Enum\OperationType;
use DateTimeInterface;

interface OperationInterface
{
    public function getId(): int;

    public function is(OperationType $type): bool;

    public function isFor(ResourceInterface $resource): bool;

    public function getPerformedAt(): DateTimeInterface;

    public function isCurrent(): bool;

    public function linkToSnapshot(SnapshotInterface $snapshot): void;

    public function getValue(ResourceInterface $requestedResource): int;
}
