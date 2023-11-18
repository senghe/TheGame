<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Galaxy\Bridge;

interface NavigatorInterface
{
    public function isWithinBoundaries(int $galaxy, int $solarSystem, int $planet): bool;

    public function isColonized(int $galaxy, int $solarSystem, int $planet): bool;
}
