<?php

declare(strict_types=1);

namespace App\Component\Resource\Application;

use App\Component\Resource\Domain\Entity\SnapshotInterface;
use App\SharedKernel\Domain\Entity\PlanetInterface;

interface RootSnapshotProviderInterface
{
    public function provide(PlanetInterface $planet): SnapshotInterface;
}