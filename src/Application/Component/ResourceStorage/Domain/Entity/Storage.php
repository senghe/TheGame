<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceStorage\Domain\Entity;

use TheGame\Application\Component\ResourceStorage\Domain\Exception\CannotUseUnsupportedResourceException;
use TheGame\Application\Component\ResourceStorage\Domain\Exception\InsufficientResourcesException;
use TheGame\Application\Component\ResourceStorage\Domain\StorageIdInterface;
use TheGame\Application\SharedKernel\Domain\PlanetIdInterface;
use TheGame\Application\SharedKernel\Domain\ResourceAmountInterface;
use TheGame\Application\SharedKernel\Domain\ResourceIdInterface;

class Storage
{
    public function __construct(
        protected readonly StorageIdInterface $storageId,
        protected readonly ResourceIdInterface $resourceId,
        protected int $currentAmount,
        protected ?int $limit = null,
    ) {
        if ($this->limit !== null && $this->currentAmount > $this->limit) {
            $this->currentAmount = $this->limit;
        }
    }

    public function getId(): StorageIdInterface
    {
        return $this->storageId;
    }

    public function supports(ResourceAmountInterface $amount): bool
    {
        return $this->resourceId->getUuid() === $amount->getResourceId()->getUuid();
    }

    public function hasEnough(ResourceAmountInterface $amount): bool
    {
        if ($this->supports($amount) === false) {
            return false;
        }

        return $this->currentAmount >= $amount->getAmount();
    }

    public function use(PlanetIdInterface $planetId, ResourceAmountInterface $amount): void
    {
        if ($this->supports($amount) === false) {
            throw new CannotUseUnsupportedResourceException(
                $planetId,
                $amount,
            );
        }

        if ($this->hasEnough($amount) === false) {
            throw new InsufficientResourcesException($planetId, $amount);
        }

        $this->currentAmount -= $amount->getAmount();
    }

    public function dispatch(int $amount): void
    {
        $this->currentAmount += $amount;

        $reachedLimit = $this->limit !== null && $this->currentAmount > $this->limit;
        if ($reachedLimit) {
            $this->currentAmount = (int) $this->limit;
        }
    }

    public function getCurrentAmount(): int
    {
        return $this->currentAmount;
    }
}
