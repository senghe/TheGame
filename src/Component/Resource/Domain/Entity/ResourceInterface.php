<?php

declare(strict_types=1);

namespace App\Component\Resource\Domain\Entity;

use App\SharedKernel\Domain\Entity\ResourceInterface as BaseResourceInterface;

interface ResourceInterface extends BaseResourceInterface
{
    public function is(ResourceInterface $resource): bool;
}
