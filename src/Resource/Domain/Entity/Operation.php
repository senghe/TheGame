<?php

declare(strict_types=1);

namespace App\Resource\Domain\Entity;

use App\Resource\Domain\Exception\NoValueFoundForResourceException;
use App\SharedKernel\DoctrineEntityTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Operation implements OperationInterface
{
    use DoctrineEntityTrait;

    private ?SnapshotInterface $snapshot;

    private string $code;

    /**
     * @var Collection<OperationValueInterface>
     */
    private Collection $operationValues;

    private DateTime $performedAt;

    public function __construct()
    {
        $this->operationValues = new ArrayCollection();
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
