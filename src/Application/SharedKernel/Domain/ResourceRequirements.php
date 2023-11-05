<?php

declare(strict_types=1);

namespace TheGame\Application\SharedKernel\Domain;

final class ResourceRequirements implements ResourceRequirementsInterface
{
    /** @var array<string, ResourceAmountInterface> */
    private array $requirements = [];

    public function add(ResourceAmountInterface $resourceAmount): void
    {
        $resourceId = $resourceAmount->getResourceId();
        if ($this->hasResource($resourceId)) {
            $this->appendResource($resourceAmount);

            return;
        }

        $this->requirements[$resourceId->getUuid()] = $resourceAmount;
    }

    public function getAmount(ResourceIdInterface $resourceId): int
    {
        if ($this->hasResource($resourceId) === false) {
            return 0;
        }

        return $this->requirements[$resourceId->getUuid()]->getAmount();
    }

    private function hasResource(ResourceIdInterface $resourceId): bool
    {
        return isset($this->requirements[$resourceId->getUuid()]);
    }

    private function appendResource(ResourceAmountInterface $incomingResourceAmount): void
    {
        $resourceId = $incomingResourceAmount->getResourceId();
        $currentResourceAmount = $this->requirements[$resourceId->getUuid()];

        $this->requirements[$resourceId->getUuid()] = new ResourceAmount(
            $resourceId,
            $currentResourceAmount->getAmount() + $incomingResourceAmount->getAmount(),
        );
    }

    /** @return array<string, int> */
    public function toScalarArray(): array
    {
        $retVal = [];
        foreach ($this->requirements as $resourceUuid => $resourceAmount) {
            $retVal[$resourceUuid] = $resourceAmount->getAmount();
        }

        return $retVal;
    }

    /** @return array<int, ResourceAmountInterface> */
    public function getAll(): array
    {
        return array_values($this->requirements);
    }
}
