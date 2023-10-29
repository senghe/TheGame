<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceStorage\Domain\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\Collection;
use TheGame\Application\Component\ResourceStorage\Domain\Exception\CannotUseUnsupportedResourceException;
use TheGame\Application\Component\ResourceStorage\Domain\StoragesCollectionIdInterface;
use TheGame\Application\SharedKernel\Domain\PlanetId;
use TheGame\Application\SharedKernel\Domain\ResourceAmount;

class StoragesCollection
{
    public function __construct(
        protected readonly StoragesCollectionIdInterface $id,
        protected readonly PlanetId $planetId,
        protected Collection $storages,
        protected DateTimeInterface $updatedAt,
    ) {
    }

    public function getId(): StoragesCollectionIdInterface
    {
        return $this->id;
    }

    public function add(Storage $storage): void
    {
        $this->storages->add($storage);
    }

    public function supports(ResourceAmount $resourceAmount): bool
    {
        foreach ($this->storages as $storage) {
            if ($storage->supports($resourceAmount) === true) {
                return true;
            }
        }

        return false;
    }

    public function hasEnough(ResourceAmount $resourceAmount): bool
    {
        foreach ($this->storages as $storage) {
            if ($storage->supports($resourceAmount) === true) {
                return $storage->hasEnough($resourceAmount);
            }
        }

        return false;
    }

    public function use(ResourceAmount $resourceAmount): void
    {
        foreach ($this->storages as $storage) {
            if ($storage->supports($resourceAmount) === true) {
                $storage->use($this->planetId, $resourceAmount);

                return;
            }
        }

        throw new CannotUseUnsupportedResourceException(
            $this->planetId,
            $resourceAmount,
        );
    }

    public function dispatch(ResourceAmount $resourceAmount): void
    {
        foreach ($this->storages as $storage) {
            if ($storage->supports($resourceAmount)) {
                $storage->dispatch($resourceAmount);
            }
        }
    }
}
