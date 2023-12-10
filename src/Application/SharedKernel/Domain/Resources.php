<?php

declare(strict_types=1);

namespace TheGame\Application\SharedKernel\Domain;

use TheGame\Application\SharedKernel\Domain\EntityId\ResourceId;
use TheGame\Application\SharedKernel\Domain\EntityId\ResourceIdInterface;

final class Resources implements ResourcesInterface
{
    /** @var array<string, ResourceAmountInterface> */
    private array $resources = [];

    public function add(ResourcesInterface $resources): void
    {
        foreach ($resources->toScalarArray() as $resourceId => $amount) {
            $this->addResource(new ResourceAmount(
                new ResourceId($resourceId),
                $amount
            ));
        }
    }

    public function addResource(ResourceAmountInterface $resourceAmount): void
    {
        $resourceId = $resourceAmount->getResourceId();
        if ($this->hasResource($resourceId)) {
            $this->appendResource($resourceAmount);

            return;
        }

        $this->resources[$resourceId->getUuid()] = $resourceAmount;
    }

    public function getAmount(ResourceIdInterface $resourceId): int
    {
        if ($this->hasResource($resourceId) === false) {
            return 0;
        }

        return $this->resources[$resourceId->getUuid()]->getAmount();
    }

    private function hasResource(ResourceIdInterface $resourceId): bool
    {
        return isset($this->resources[$resourceId->getUuid()]);
    }

    private function appendResource(ResourceAmountInterface $incomingResourceAmount): void
    {
        $resourceId = $incomingResourceAmount->getResourceId();
        $currentResourceAmount = $this->resources[$resourceId->getUuid()];

        $this->resources[$resourceId->getUuid()] = new ResourceAmount(
            $resourceId,
            $currentResourceAmount->getAmount() + $incomingResourceAmount->getAmount(),
        );
    }

    /** @param array<string, int> $scalarArray */
    public static function fromScalarArray(array $scalarArray): self
    {
        $resources = new self();

        foreach ($scalarArray as $resourceId => $quantity) {
            $resources->addResource(new ResourceAmount(
                new ResourceId($resourceId),
                $quantity,
            ));
        }

        return $resources;
    }

    /** @return array<string, int> */
    public function toScalarArray(): array
    {
        $retVal = [];
        foreach ($this->resources as $resourceUuid => $resourceAmount) {
            $retVal[$resourceUuid] = $resourceAmount->getAmount();
        }

        return $retVal;
    }

    /** @return array<int, ResourceAmountInterface> */
    public function getAll(): array
    {
        return array_values($this->resources);
    }

    public function multipliedBy(int $quantity): ResourcesInterface
    {
        $newResources = new self();
        foreach ($this->resources as $resourceAmount) {
            $newAmount = new ResourceAmount(
                $resourceAmount->getResourceId(),
                $resourceAmount->getAmount() * $quantity,
            );
            $newResources->addResource($newAmount);
        }

        return $newResources;
    }

    public function sum(): int
    {
        $sum = 0;

        foreach ($this->resources as $resourceAmount) {
            $sum += $resourceAmount->getAmount();
        }

        return $sum;
    }

    public function clear(): void
    {
        $this->resources = [];
    }
}
