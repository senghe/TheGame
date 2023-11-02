<?php

declare(strict_types=1);

namespace TheGame\Application\SharedKernel\Domain;

final class ResourceRequirements implements ResourceRequirementsInterface
{
    /** @var array<string, ResourceAmountInterface> */
    private array $resources = [];

    public function add(ResourceAmountInterface $resourceAmount): void
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

    /** @return array<int, ResourceAmountInterface> */
    public function getAll(): array
    {
        return array_values($this->resources);
    }
}
