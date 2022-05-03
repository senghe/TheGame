<?php

declare(strict_types=1);

namespace App\Resource\Domain\Factory;

use App\Resource\Domain\Entity\ResourceInterface;
use App\Resource\Domain\Entity\SnapshotInterface;
use Doctrine\Common\Collections\Collection;

interface SnapshotFactoryInterface
{
    /**
     * @var Collection<ResourceInterface>
     */
    public function createFirstInLine(Collection $resources): SnapshotInterface;

    public function create(SnapshotInterface $previous): SnapshotInterface;
}
