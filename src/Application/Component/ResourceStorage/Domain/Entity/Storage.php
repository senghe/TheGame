<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceStorage\Domain\Entity;

use TheGame\Application\Component\ResourceStorage\Domain\Exception\CannotUseUnsupportedResourceException;
use TheGame\Application\Component\ResourceStorage\Domain\StorageId;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;
use TheGame\Application\SharedKernel\Domain\ResourceAmount;
use TheGame\Application\SharedKernel\Domain\ResourceIdInterface;

class Storage
{
    public function __construct(
        protected readonly StorageId $storageId,
        protected readonly ResourceIdInterface $resourceId,
        protected int $currentAmount,
        protected ?int $limit = null,
    ) {
    }

    public function getId(): StorageId
    {
        return $this->storageId;
    }

    public function supports(ResourceAmount $amount): bool
    {
        return $this->resourceId === $amount->resourceId;
    }

    public function hasEnough(ResourceAmount $amount): bool
    {
        if ($this->resourceId !== $amount->resourceId) {
            return false;
        }

        return $this->currentAmount >= $amount->amount;
    }

    public function use(PlanetIdInterface $planetId, ResourceAmount $amount): void
    {
        if ($this->resourceId !== $amount->resourceId) {
            throw new CannotUseUnsupportedResourceException(
                $planetId,
                $amount,
            );
        }

        $this->currentAmount -= $amount->amount;
    }

    public function dispatch(int $amount): void
    {
        $this->currentAmount += $amount;

        $reachedLimit = $this->limit !== null && $this->currentAmount > $this->limit;
        if ($reachedLimit) {
            $this->currentAmount = $this->limit;
        }
    }
}
