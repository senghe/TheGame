<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceStorage\Domain\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use TheGame\Application\Component\ResourceStorage\Domain\Exception\CannotUseUnsupportedResourceException;
use TheGame\Application\Component\ResourceStorage\Domain\Exception\InsufficientResourcesException;
use TheGame\Application\Component\ResourceStorage\Domain\StoragesCollectionIdInterface;
use TheGame\Application\SharedKernel\Domain\PlanetId;
use TheGame\Application\SharedKernel\Domain\ResourceAmountInterface;
use TheGame\Application\SharedKernel\Domain\ResourceRequirementsInterface;

class StoragesCollection
{
    /** @var Collection<int, Storage> */
    protected Collection $storages;

    public function __construct(
        protected readonly StoragesCollectionIdInterface $id,
        protected readonly PlanetId $planetId,
        protected DateTimeInterface $updatedAt,
    ) {
        $this->storages = new ArrayCollection([]);
    }

    public function getId(): StoragesCollectionIdInterface
    {
        return $this->id;
    }

    public function add(Storage $storage): void
    {
        $this->storages->add($storage);
    }

    public function supports(ResourceAmountInterface $resourceAmount): bool
    {
        foreach ($this->storages as $storage) {
            if ($storage->supports($resourceAmount) === true) {
                return true;
            }
        }

        return false;
    }

    public function hasEnough(ResourceRequirementsInterface $requirements): bool
    {
        $requirementsArray = $requirements->getAll();
        foreach ($this->storages as $storage) {
            foreach ($requirementsArray as $requirement) {
                if ($storage->supports($requirement) === false) {
                    return false;
                }
            }
        }

        foreach ($requirementsArray as $requirement) {
            foreach ($this->storages as $storage) {
                if ($storage->supports($requirement) === false) {
                    continue;
                }

                if ($storage->hasEnough($requirement) === false) {
                    return false;
                }
            }
        }

        return true;
    }

    public function use(ResourceAmountInterface $resourceAmount): void
    {
        foreach ($this->storages as $storage) {
            if ($storage->supports($resourceAmount) === true) {
                if ($storage->hasEnough($resourceAmount)) {
                    $storage->use($this->planetId, $resourceAmount);

                    return;
                }

                throw new InsufficientResourcesException(
                    $this->planetId,
                    $resourceAmount,
                );
            }
        }

        throw new CannotUseUnsupportedResourceException(
            $this->planetId,
            $resourceAmount,
        );
    }

    public function dispatch(ResourceAmountInterface $resourceAmount): void
    {
        foreach ($this->storages as $storage) {
            if ($storage->supports($resourceAmount)) {
                $storage->dispatch($resourceAmount->getAmount());

                return;
            }
        }
    }
}
