<?php

declare(strict_types=1);

namespace App\Component\Resource\Domain\Entity;

use App\Component\Resource\Domain\Enum\OperationType;
use App\Component\Resource\Domain\Exception\CannotPerformOperationException;
use App\Component\Resource\Domain\Exception\WorkingOnLockedStorageException;
use App\Component\SharedKernel\DoctrineEntityTrait;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Storage implements StorageInterface
{
    use DoctrineEntityTrait;

    private const ONE_HOUR_IN_SECONDS = 3600;

    private SnapshotInterface $snapshot;

    private ?int $initialAmount = 0;

    private ?int $maxAmount = 0;

    private ResourceInterface $resource;

    /**
     * @var Collection<OperationInterface>
     */
    private Collection $operations;

    private DateTimeInterface $createdAt;

    private DateTimeInterface $updatedAt;

    private ?DateTimeInterface $lockedAt = null;

    public function __construct(
        ResourceInterface $resource,
        int $initialAmount,
        int $maxAmount
    ) {
        $this->resource = $resource;
        $this->initialAmount = $initialAmount;
        $this->maxAmount = $maxAmount;

        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    public function isFor(ResourceInterface $resource): bool
    {
        return $this->resource->is($resource);
    }

    public function getAmount(): int
    {
        $amount = $this->initialAmount;

        $amountChangeOperations = $this->filterOperations(OperationType::AmountChange);
        foreach ($amountChangeOperations as $operation) {
            $amount += $operation->getValue($this->resource);
        }

        $increasedAmount = 0;
        $speedChangeOperations = $this->filterOperations(OperationType::ChangeSpeed);
        foreach ($speedChangeOperations as $key => $operation) {
            if ($operation->isCurrent() === false) {
                break;
            }

            if ($operation->isFor($this->resource) === false) {
                continue;
            }

            $mostEndingTime = new DateTimeImmutable();
            if ($this->isLocked() === true) {
                $mostEndingTime = $this->lockedAt;
            }

            $dateFrom = $operation->getPerformedAt();
            $dateTo = $key === $speedChangeOperations->count()-1 ? $mostEndingTime : $speedChangeOperations->get($key+1)->getPerformedAt();

            $timeInterval = $dateTo->getTimestamp() - $dateFrom->getTimestamp();
            $step = $operation->getValue($this->resource) / self::ONE_HOUR_IN_SECONDS;
            $increasedAmount += floor($step * $timeInterval);
        }

        $amount += $increasedAmount;

        return $this->isFull($amount) ? $this->maxAmount : $amount;
    }

    /**
     * @return Collection<OperationInterface>
     */
    private function filterOperations(OperationType $type): Collection
    {
        $filteredOperations = new ArrayCollection();

        foreach ($this->operations as $operation) {
            if ($operation->is($type)) {
                $filteredOperations->add($operation);
            }
        }

        return $filteredOperations;
    }

    private function isFull(int $amount): bool
    {
        return $amount >= (int) $this->maxAmount;
    }

    public function accept(OperationInterface $operation): void
    {
        if ($this->isLocked()) {
            throw new WorkingOnLockedStorageException($this);
        }

        $operationValue = $operation->getValue($this->resource);
        if (-$operationValue > $this->getAmount()) {
            throw new CannotPerformOperationException($operation);
        }

        $this->operations->add($operation);

        $this->updatedAt = new DateTime();
    }

    public function isClosed(): bool
    {
        return $this->operations->count() === self::MAX_OPERATIONS_COUNT;
    }

    public function cloneFor(SnapshotInterface $snapshot): StorageInterface
    {
        $newStorage = clone $this;
        $newStorage->id = null;
        $newStorage->snapshot = $snapshot;
        $newStorage->initialAmount = $this->getAmount();

        $speedChangeOperations = $this->filterOperations(OperationType::ChangeSpeed);
        $newStorage->operations = new ArrayCollection([
            $speedChangeOperations->last()
        ]);

        $newStorage->updatedAt = new DateTime();
        $newStorage->createdAt = new DateTime();

        return $newStorage;
    }

    public function lock(): void
    {
        $this->lockedAt = new DateTime();
    }

    private function isLocked(): bool
    {
        return $this->lockedAt !== null;
    }
}
