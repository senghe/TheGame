<?php

declare(strict_types=1);

namespace App\Component\Building\Domain\ValueObject;

use App\Component\SharedKernel\Domain\Entity\ResourceInterface;

interface ResourceMiningSpeedInterface
{
    public function getResource(): ResourceInterface;

    public function getSpeed(): int;
}