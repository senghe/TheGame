<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Galaxy\Domain\Entity;

use TheGame\Application\Component\Galaxy\Domain\Exception\PlanetAlreadyColonizedException;
use TheGame\Application\Component\Galaxy\Domain\Exception\PlanetNotColonizedException;
use TheGame\Application\Component\Galaxy\Domain\SolarSystemIdInterface;
use TheGame\Application\SharedKernel\Domain\EntityId\PlanetIdInterface;
use TheGame\Application\SharedKernel\Domain\EntityId\PlayerIdInterface;
use TheGame\Application\SharedKernel\Domain\GalaxyPoint;

class SolarSystem
{
    /** @var Planet[] */
    protected array $planets;

    public function __construct(
        protected readonly SolarSystemIdInterface $solarSystemId,
        protected readonly int $galaxyNumber,
        protected readonly int $solarSystemNumber,
    ) {

    }

    public function getId(): SolarSystemIdInterface
    {
        return $this->solarSystemId;
    }

    public function getGalaxyNumber(): int
    {
        return $this->galaxyNumber;
    }

    public function getSolarSystemNumber(): int
    {
        return $this->solarSystemNumber;
    }

    public function getPlayerId(int $planetPosition): PlayerIdInterface
    {
        foreach ($this->planets as $planet) {
            if ($planet->isOnPosition($planetPosition)) {
                return $planet->getPlayerId();
            }
        }

        throw new PlanetNotColonizedException(
            new GalaxyPoint($this->galaxyNumber, $this->solarSystemNumber, $planetPosition)
        );
    }

    public function getPlanetId(int $planetPosition): PlanetIdInterface
    {
        foreach ($this->planets as $planet) {
            if ($planet->isOnPosition($planetPosition)) {
                return $planet->getId();
            }
        }

        throw new PlanetNotColonizedException(
            new GalaxyPoint($this->galaxyNumber, $this->solarSystemNumber, $planetPosition)
        );
    }

    public function getPlanetPosition(PlanetIdInterface $planetId): int
    {
        foreach ($this->planets as $planet) {
            if ($planet->getId()->getUuid() === $planetId->getUuid()) {
                return $planet->getPosition();
            }
        }

        throw new PlanetNotColonizedException(
            new GalaxyPoint($this->galaxyNumber, $this->solarSystemNumber, $planetPosition)
        );
    }

    public function isColonized(int $planetPosition): bool
    {
        foreach ($this->planets as $planet) {
            if ($planet->isOnPosition($planetPosition)) {
                return true;
            }
        }

        return false;
    }

    public function colonize(Planet $planetToColonize): void
    {
        foreach ($this->planets as $planet) {
            if ($planet->isOnPosition($planetToColonize->getPosition())) {
                throw new PlanetAlreadyColonizedException(
                    new GalaxyPoint(
                        $this->galaxyNumber, $this->solarSystemNumber, $planetToColonize->getPosition(),
                    ),
                    $planetToColonize->getPlayerId()
                );
            }
        }

        $this->planets[] = $planetToColonize;
    }
}
