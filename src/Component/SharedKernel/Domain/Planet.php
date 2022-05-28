<?php

declare(strict_types=1);

namespace App\Component\SharedKernel\Domain;

use App\Component\Building\Domain\Entity\BuildingInterface;
use App\Component\Building\Domain\Entity\PlanetInterface;
use App\Component\Building\Domain\ValueObject\ResourceAmountInterface;
use App\Component\Resource\Domain\Entity\SnapshotInterface;
use App\Component\SharedKernel\DoctrineEntityTrait;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Webmozart\Assert\Assert;

class Planet implements PlanetInterface
{
    use DoctrineEntityTrait;

    private SnapshotInterface $resourceSnapshot;

    private string $name;

    /**
     * @var Collection<BuildingInterface>
     */
    private Collection $buildings;

    private DateTime $settledAt;

    /**
     * @param Collection<ResourceAmountInterface> $resourcesAmounts
     */
    public function hasEnoughResources(Collection $resourcesAmounts): bool
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