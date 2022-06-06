<?php

declare(strict_types=1);

namespace App\SharedKernel\Domain\Entity;

use App\Component\Building\Domain\ValueObject\ResourceAmountInterface;
use App\SharedKernel\Port\CollectionInterface;

interface PlanetInterface
{
    public function getId(): int;

    public function isInitial(): bool;

    /**
     * @param CollectionInterface<ResourceAmountInterface> $resourcesAmounts
     */
    public function hasEnoughResources(CollectionInterface $resourcesAmounts): bool;
}