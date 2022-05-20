<?php

declare(strict_types=1);

namespace App\Domain\Resource\Exception;

use App\Domain\Resource\Entity\OperationInterface;
use LogicException;

final class CannotPerformOperationException extends LogicException
{
    private OperationInterface $operation;

    public function __construct(OperationInterface $operation)
    {
        parent::__construct();

        $this->operation = $operation;
    }

    public function getOperation(): OperationInterface
    {
        return $this->operation;
    }
}