<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Galaxy\EventListener;

use TheGame\Application\Component\FleetJourney\Domain\Event\FleetHasReachedJourneyTargetPointEvent;
use TheGame\Application\Component\Galaxy\Domain\Event\PlanetHasBeenColonizedEvent;
use TheGame\Application\Component\Galaxy\Domain\Exception\PlanetAlreadyColonizedException;
use TheGame\Application\Component\Galaxy\Domain\Factory\PlanetFactoryInterface;
use TheGame\Application\Component\Galaxy\Domain\Factory\SolarSystemFactoryInterface;
use TheGame\Application\Component\Galaxy\SolarSystemRepositoryInterface;
use TheGame\Application\Component\Player\Bridge\PlayerContextInterface;
use TheGame\Application\SharedKernel\Domain\GalaxyPoint;
use TheGame\Application\SharedKernel\Domain\FleetMissionType;
use TheGame\Application\SharedKernel\EventBusInterface;

final class ColonizePlanetListener
{
    public function __construct(
        private readonly SolarSystemRepositoryInterface $solarSystemRepository,
        private readonly SolarSystemFactoryInterface $solarSystemFactory,
        private readonly PlanetFactoryInterface $planetFactory,
        private readonly PlayerContextInterface $playerContext,
        private readonly EventBusInterface $eventBus,
    ) {

    }

    public function __invoke(FleetHasReachedJourneyTargetPointEvent $event): void
    {
        if ($event->getMission() !== FleetMissionType::Colonization->value) {
            return;
        }

        $targetPoint = GalaxyPoint::fromString($event->getTargetGalaxyPoint());

        $solarSystem = $this->solarSystemRepository->findByGalaxyPoint($targetPoint);
        if ($solarSystem === null) {
            $solarSystem = $this->solarSystemFactory->create(
                $targetPoint->getGalaxy(),
                $targetPoint->getSolarSystem()
            );
        }

        $playerId = $this->playerContext->getCurrentPlayerId();
        if ($solarSystem->isColonized($targetPoint->getPlanet()) === true) {
            throw new PlanetAlreadyColonizedException($targetPoint, $playerId);
        }

        $planet = $this->planetFactory->create($playerId, $targetPoint);
        $solarSystem->colonize($planet);

        $this->eventBus->dispatch(
            new PlanetHasBeenColonizedEvent(
                $playerId->getUuid(),
                $event->getTargetGalaxyPoint(),
            )
        );
    }
}
