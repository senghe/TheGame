<?php

declare(strict_types=1);

namespace App\Component\Resource\Domain;

use App\Component\Resource\Domain\Entity\OperationInterface;
use App\Component\SharedKernel\AggregateRootInterface as BaseAggregateRootInterface;
use App\Component\SharedKernel\Domain\Entity\PlanetInterface;

interface AggregateRootInterface extends BaseAggregateRootInterface
{
    public function build(PlanetInterface $planet): void;

    public function performOperation(OperationInterface $operation): void;
}
