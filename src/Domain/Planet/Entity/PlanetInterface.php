<?php

declare(strict_types=1);

namespace App\Domain\Planet\Entity;

interface PlanetInterface
{
    public function hasEnoughPlace(int $neededPlace): bool;
}