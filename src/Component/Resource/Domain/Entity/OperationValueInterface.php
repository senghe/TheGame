<?php

declare(strict_types=1);

namespace App\Component\Resource\Domain\Entity;

interface OperationValueInterface
{
    public function isFor(ResourceInterface $resource): bool;

    public function getValue(): int;
}
