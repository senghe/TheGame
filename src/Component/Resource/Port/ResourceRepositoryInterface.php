<?php

declare(strict_types=1);

namespace App\Component\Resource\Port;

use App\SharedKernel\Domain\Entity\ResourceInterface;

interface ResourceRepositoryInterface
{
    public function findOneByCode(string $resourceCode): ResourceInterface;
}