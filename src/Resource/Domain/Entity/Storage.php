<?php

declare(strict_types=1);

namespace App\Resource\Domain\Entity;

use App\Resource\Domain\ResourceStorageViewModel;
use App\Resource\Domain\ResourceStorageViewModelInterface;
use App\SharedKernel\DoctrineEntityTrait;
use DateTime;
use DateTimeInterface;

class Storage implements StorageInterface
{
    use DoctrineEntityTrait;

    private const ONE_HOUR_IN_SECONDS = 3600;

    private SnapshotInterface $snapshot;

    private ?int $amount = 0;

    private ?int $increaseSpeed = 0;

    private ?int $maxAmount = 0;

    private ResourceInterface $resource;

    private DateTimeInterface $createdAt;

    private DateTimeInterface $updatedAt;

    public function __construct(
        ResourceInterface $resource,
        int $amount,
        int $increaseSpeed,
        int $maxAmount
    ) {
        $this->resource = $resource;
        $this->amount = $amount;
        $this->increaseSpeed = $increaseSpeed;
        $this->maxAmount = $maxAmount;

        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    public function getCurrentAmount(): int
    {
        $timeInterval = (new DateTime())->getTimestamp() - $this->createdAt->getTimestamp();
        $step = $this->increaseSpeed / self::ONE_HOUR_IN_SECONDS;
        $currentAmount = (int) floor($this->amount + $step * $timeInterval);

        return $this->isFull($currentAmount) ? $this->maxAmount : $currentAmount;
    }

    private function isFull(int $amount): bool
    {
        return $amount >= (int) $this->maxAmount;
    }

    public function performOperation(OperationInterface $operation): void
    {
        $this->amount += $operation->getValue($this->resource);
    }

    public function toViewModel(): ResourceStorageViewModelInterface
    {
        $currentAmount = $this->getCurrentAmount();

        return new ResourceStorageViewModel(
            $this->resource->getCode(),
            $currentAmount,
            $this->isFull($currentAmount)
        );
    }

    public function cloneFor(SnapshotInterface $snapshot): StorageInterface
    {
        $newStorage = clone $this;
        $newStorage->id = null;
        $newStorage->snapshot = $snapshot;
        $newStorage->amount = $this->getCurrentAmount();
        $newStorage->updatedAt = new DateTime();
        $newStorage->createdAt = new DateTime();

        return $newStorage;
    }
}
