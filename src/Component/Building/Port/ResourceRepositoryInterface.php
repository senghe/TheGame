<?php

declare(strict_types=1);

namespace App\Component\Building\Port;

use App\SharedKernel\Port\CollectionInterface;

interface ResourceRepositoryInterface
{
    public function findAll(): CollectionInterface;
}