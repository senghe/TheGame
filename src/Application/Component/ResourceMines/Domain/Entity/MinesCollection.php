<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceMines\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use TheGame\Application\Component\ResourceMines\Domain\Exception\CannotUpgradeMiningSpeedForUnsupportedResourceException;
use TheGame\Application\Component\ResourceMines\Domain\MinesCollectionIdInterface;
use TheGame\Application\SharedKernel\Domain\EntityId\PlanetIdInterface;
use TheGame\Application\SharedKernel\Domain\EntityId\ResourceIdInterface;
use TheGame\Application\SharedKernel\Domain\ResourceAmountInterface;

class MinesCollection
{
    /** @var Collection<int, Mine> */
    private Collection $mines;

    public function __construct(
        protected readonly MinesCollectionIdInterface $id,
        protected readonly PlanetIdInterface $planetId,
    ) {
        $this->mines = new ArrayCollection([]);
    }

    public function getId(): MinesCollectionIdInterface
    {
        return $this->id;
    }

    public function isEmpty(): bool
    {
        return $this->mines->isEmpty();
    }

    /** @return ResourceAmountInterface[] */
    public function extract(): array
    {
        $result = [];
        foreach ($this->mines as $mine) {
            $result[] = $mine->extract();
        }

        return $result;
    }

    public function addMine(Mine $mine): void
    {
        if ($this->mines->contains($mine) === false) {
            $this->mines->add($mine);
        }
    }

    public function upgradeMiningSpeed(
        ResourceIdInterface $resourceId,
        int $newSpeed
    ): void {
        foreach ($this->mines as $mine) {
            if ($mine->isForResource($resourceId) === true) {
                $mine->upgradeMiningSpeed($newSpeed);

                return;
            }
        }

        throw new CannotUpgradeMiningSpeedForUnsupportedResourceException(
            $this->planetId,
            $resourceId,
            $newSpeed
        );
    }

    public function hasMineForResource(ResourceIdInterface $resourceId): bool
    {
        foreach ($this->mines as $mine) {
            if ($mine->isForResource($resourceId) === true) {
                return true;
            }
        }

        return false;
    }
}
