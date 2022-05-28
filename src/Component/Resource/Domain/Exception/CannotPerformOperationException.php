<?php

declare(strict_types=1);

namespace App\Component\Resource\Domain\Exception;

use App\Component\Resource\Domain\Entity\OperationInterface;
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