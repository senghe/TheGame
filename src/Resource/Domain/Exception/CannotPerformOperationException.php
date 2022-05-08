<?php

declare(strict_types=1);

namespace App\Resource\Domain\Exception;

use App\Resource\Domain\Entity\OperationInterface;
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