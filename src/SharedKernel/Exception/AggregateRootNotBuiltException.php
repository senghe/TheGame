<?php

namespace App\SharedKernel\Exception;

use App\SharedKernel\AggregateRootInterface;
use LogicException;

final class AggregateRootNotBuiltException extends LogicException
{
    private AggregateRootInterface $aggregateRoot;

    public function __construct(AggregateRootInterface $aggregateRoot)
    {
        parent::__construct();

        $this->aggregateRoot = $aggregateRoot;
    }

    public function getAggregateRoot(): AggregateRootInterface
    {
        return $this->aggregateRoot;
    }
}
