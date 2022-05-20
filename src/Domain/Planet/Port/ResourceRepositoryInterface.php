<?php

declare(strict_types=1);

namespace App\Domain\Planet\Port;

use Doctrine\Common\Collections\Collection;

interface ResourceRepositoryInterface
{
    public function findAll(): Collection;
}