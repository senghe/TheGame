<?php

declare(strict_types=1);

namespace App\Component\Resource\Domain\Factory;

use App\Component\Resource\Domain\Entity\Operation;
use App\Component\Resource\Domain\Entity\OperationInterface;
use App\Component\Resource\Domain\Entity\ResourceInterface;
use App\Component\Resource\Domain\Enum\OperationType;

final class OperationFactory implements OperationFactoryInterface
{
    private OperationValueFactoryInterface $operationValueFactory;

    public function __construct(OperationValueFactoryInterface $operationValueFactory)
    {
        $this->operationValueFactory = $operationValueFactory;
    }

    public function createSpeedChange(
        ResourceInterface $resource,
        int $value
    ): OperationInterface {
        return new Operation(
            OperationType::ChangeSpeed, [
                $this->operationValueFactory->create(
                    $resource,
                    $value
                )
            ]
        );
    }
}