<?php

declare(strict_types=1);

namespace App\Component\Resource\Domain\Entity;

use App\Component\Resource\Domain\Enum\OperationType;
use App\Component\Resource\Domain\Exception\NoValueFoundForResourceException;
use App\Component\SharedKernel\DoctrineEntityTrait;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Webmozart\Assert\Assert;

class Operation implements OperationInterface
{
    use DoctrineEntityTrait;

    private ?SnapshotInterface $snapshot;

    private OperationType $type;

    /**
     * @var Collection<OperationValueInterface>
     */
    private Collection $operationValues;

    private DateTimeInterface $performedAt;

    public function __construct(OperationType $type, array $operationValues=[])
    {
        $this->type = $type;

        Assert::allIsInstanceOf($operationValues, OperationValueInterface::class);
        $this->operationValues = new ArrayCollection($operationValues);
    }

    public function is(OperationType $type): bool
    {
        return $this->type === $type;
    }

    public function isFor(ResourceInterface $resource): bool
    {
        foreach ($this->operationValues as $value) {
            if ($value->isFor($resource)) {
                return true;
            }
        }

        return false;
    }

    public function getPerformedAt(): DateTimeInterface
    {
        return $this->performedAt;
    }

    public function isCurrent(): bool
    {
        return $this->performedAt <= new DateTimeImmutable();
    }

    public function linkToSnapshot(SnapshotInterface $snapshot): void
    {
        $this->snapshot = $snapshot;
        $this->performedAt = new DateTime();
    }

    public function getValue(ResourceInterface $requestedResource): int
    {
        foreach ($this->operationValues as $value) {
            if ($value->isFor($requestedResource)) {
                return $value->getValue();
            }
        }

        throw new NoValueFoundForResourceException($requestedResource);
    }
}
