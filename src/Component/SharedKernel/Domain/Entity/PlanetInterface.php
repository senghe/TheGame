<?php

declare(strict_types=1);

namespace App\Component\SharedKernel\Domain\Entity;

use App\Component\Building\Domain\ValueObject\ResourceAmountInterface;
use Doctrine\Common\Collections\Collection;

interface PlanetInterface
{
    public function getId(): int;

    public function isInitial(): bool;

    /**
     * @param Collection<ResourceAmountInterface> $resourcesAmounts
     */
    public function hasEnoughResources(Collection $resourcesAmounts): bool;
}