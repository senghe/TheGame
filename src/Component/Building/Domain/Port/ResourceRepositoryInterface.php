<?php

declare(strict_types=1);

namespace App\Component\Building\Domain\Port;

use Doctrine\Common\Collections\Collection;

interface ResourceRepositoryInterface
{
    public function findAll(): Collection;
}