<?php

declare(strict_types=1);

namespace TheGame\Application\SharedKernel\Domain;

interface GalaxyPointInterface
{
    public function getGalaxy(): int;

    public function getSolarSystem(): int;

    public function getPlanet(): int;

    public function format(): string;

    /** @return int[] */
    public function toArray(): array;
}
