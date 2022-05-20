<?php

declare(strict_types=1);

namespace App\Domain\Planet\Entity;

use App\Domain\Planet\ValueObject\ResourceAmountInterface;
use App\Domain\Resource\Entity\SnapshotInterface;
use App\SharedKernel\DoctrineEntityTrait;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\Collection;
use Webmozart\Assert\Assert;

class Planet implements PlanetInterface
{
    use DoctrineEntityTrait;

    private SnapshotInterface $resourceSnapshot;

    private string $name;

    private int $fieldsCount;

    private int $maxFieldsCount;

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

    public function hasEnoughPlace(int $neededPlace): bool
    {
        return $this->maxFieldsCount >= $this->fieldsCount + $neededPlace;
    }
}