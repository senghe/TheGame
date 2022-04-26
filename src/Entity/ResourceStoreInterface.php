<?php

declare(strict_types=1);

namespace App\Entity;

interface ResourceStoreInterface
{
    public function getAmount(): int;

    public function isFull(): bool;
}