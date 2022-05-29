<?php

declare(strict_types=1);

namespace App\Component\Resource\Domain\Port;

use App\Component\SharedKernel\Domain\Entity\ResourceInterface;

interface ResourceRepositoryInterface
{
    public function findOneByCode(string $resourceCode): ResourceInterface;
}