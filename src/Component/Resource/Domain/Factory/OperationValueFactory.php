<?php

declare(strict_types=1);

namespace App\Component\Resource\Domain\Factory;

use App\Component\Resource\Domain\Entity\OperationValue;
use App\Component\Resource\Domain\Entity\OperationValueInterface;
use App\Component\Resource\Domain\Entity\ResourceInterface;

class OperationValueFactory implements OperationValueFactoryInterface
{
    public function create(
        ResourceInterface $resource,
        int $value
    ): OperationValueInterface {
        return new OperationValue(
            $resource, $value
        );
    }
}