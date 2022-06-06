<?php

declare(strict_types=1);

namespace App\SharedKernel\Domain\Entity;

use App\Component\Building\Domain\Entity\BuildingInterface;
use App\Component\Building\Domain\ValueObject\ResourceAmountInterface;
use App\Component\Resource\Domain\Entity\SnapshotInterface;
use App\SharedKernel\DoctrineEntityTrait;
use App\SharedKernel\Port\CollectionInterface;
use DateTime;
use Webmozart\Assert\Assert;

class Planet implements PlanetInterface
{
    use DoctrineEntityTrait;

    private SnapshotInterface $resourceSnapshot;

    private string $name;

    /**
     * @var CollectionInterface<BuildingInterface>
     */
    private CollectionInterface $buildings;

    private DateTime $settledAt;

    /**
     * @param CollectionInterface<ResourceAmountInterface> $resourcesAmounts
     */
    public function hasEnoughResources(CollectionInterface $resourcesAmounts): bool
    {
        Assert::allIsInstanceOf($resourcesAmounts, ResourceAmountInterface::class);

        foreach ($resourcesAmounts as $resourcesAmount) {
            if ($this->resourceSnapshot->hasEnough($resourcesAmount->getResource(), $resourcesAmount->getAmount()) === false) {
                return false;
            }
        }

        return true;
    }

    public function isInitial(): bool
    {

    }
}