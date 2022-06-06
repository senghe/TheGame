<?php

declare(strict_types=1);

namespace App\Component\Resource\Domain\Entity;

use App\SharedKernel\Domain\Entity\Resource as BaseResource;

class Resource extends BaseResource implements ResourceInterface
{
    public function is(ResourceInterface $resource): bool
    {
        return $this->getCode() === $resource->getCode();
    }
}
