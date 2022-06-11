<?php

namespace App\SharedKernel\Exception;

use App\SharedKernel\AggregateInterface;
use LogicException;

final class AggregateRootNotBuiltException extends LogicException
{
    private AggregateInterface $aggregateRoot;

    public function __construct(AggregateInterface $aggregateRoot)
    {
        parent::__construct();

        $this->aggregateRoot = $aggregateRoot;
    }

    public function getAggregateRoot(): AggregateInterface
    {
        return $this->aggregateRoot;
    }
}
