<?php

declare(strict_types=1);

namespace App\SharedKernel;

interface AggregateInterface
{
    public function setAggregateRoot(EntityInterface $entity): void;
}
