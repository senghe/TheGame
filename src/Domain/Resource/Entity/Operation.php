<?php

declare(strict_types=1);

namespace App\Domain\Resource\Entity;

use App\Domain\Resource\Exception\NoValueFoundForResourceException;
use App\SharedKernel\DoctrineEntityTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Operation implements OperationInterface
{
    use DoctrineEntityTrait;

    private ?SnapshotInterface $snapshot;

    private string $code;

    private bool $isVirtual;

    /**
     * @var Collection<OperationValueInterface>
     */
    private Collection $operationValues;

    private DateTime $performedAt;

    public function __construct(bool $isVirtual=false)
    {
        $this->isVirtual = $isVirtual;
        $this->operationValues = new ArrayCollection();
    }

    public function isVirtual(): bool
    {
        return $this->isVirtual;
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
