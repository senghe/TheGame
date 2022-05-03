<?php

declare(strict_types=1);

namespace App\Resource\Domain;

use App\Resource\Domain\Entity\OperationInterface;
use App\Resource\Domain\Entity\ResourceInterface;
use App\SharedKernel\AggregateRootInterface as BaseAggregateRootInterface;
use Doctrine\Common\Collections\Collection;

interface AggregateRootInterface extends BaseAggregateRootInterface
{
    /**
     * @var Collection<ResourceInterface>
     */
    public function build(Collection $resources): void;

    public function getResources(): Collection;

    public function performOperation(OperationInterface $operation): void;
}
