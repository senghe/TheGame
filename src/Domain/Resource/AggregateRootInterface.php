<?php

declare(strict_types=1);

namespace App\Domain\Resource;

use App\Domain\Resource\Entity\OperationInterface;
use App\Domain\Resource\Entity\ResourceInterface;
use App\Domain\SharedKernel\AggregateRootInterface as BaseAggregateRootInterface;
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
