<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\CommandHandler;

use TheGame\Application\Component\Balance\Bridge\FleetJourneyContextInterface;
use TheGame\Application\Component\FleetJourney\Command\StartJourneyCommand;
use TheGame\Application\Component\FleetJourney\Domain\Entity\Fleet;
use TheGame\Application\Component\FleetJourney\Domain\Event\FleetHasStartedJourneyEvent;
use TheGame\Application\Component\FleetJourney\Domain\Exception\CannotTakeJourneyToOutOfBoundGalaxyPointException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\JourneyMissionIsNotEligibleException;
use TheGame\Application\Component\FleetJourney\Domain\Factory\JourneyFactoryInterface;
use TheGame\Application\Component\FleetJourney\Domain\MissionEligibilityCheckerInterface;
use TheGame\Application\Component\FleetJourney\FleetResolverInterface;
use TheGame\Application\Component\Galaxy\Bridge\NavigatorInterface;
use TheGame\Application\SharedKernel\Domain\EntityId\PlanetId;
use TheGame\Application\SharedKernel\Domain\GalaxyPoint;
use TheGame\Application\SharedKernel\Domain\FleetMissionType;
use TheGame\Application\SharedKernel\Domain\GalaxyPointInterface;
use TheGame\Application\SharedKernel\Domain\Resources;
use TheGame\Application\SharedKernel\EventBusInterface;

final class StartJourneyCommandHandler
{
    public function __construct(
        private readonly NavigatorInterface $galaxyNavigator,
        private readonly MissionEligibilityCheckerInterface $missionEligibilityChecker,
        private readonly FleetResolverInterface $fleetResolver,
        private readonly JourneyFactoryInterface $journeyFactory,
        private readonly FleetJourneyContextInterface $journeyContext,
        private readonly EventBusInterface $eventBus,
    ) {
    }

    public function __invoke(StartJourneyCommand $command): void
    {
        $targetGalaxyPoint = GalaxyPoint::fromString($command->getTargetGalaxyPoint());
        if ($this->galaxyNavigator->isWithinBoundaries($targetGalaxyPoint) === false) {
            throw new CannotTakeJourneyToOutOfBoundGalaxyPointException($targetGalaxyPoint);
        }

        $fleetTakingJourney = $this->fleetResolver->resolveFromPlanet(
            new PlanetId($command->getPlanetId()),
            $command->getShipsTakingJourney(),
            Resources::fromScalarArray($command->getResourcesLoad()),
            $targetGalaxyPoint
        );

        $startGalaxyPoint = $fleetTakingJourney->getStationingGalaxyPoint();
        $missionType = FleetMissionType::from($command->getMissionType());
        $isMissionEligible = $this->isMissionEligible(
            $missionType, $startGalaxyPoint, $targetGalaxyPoint, $fleetTakingJourney,
        );
        if ($isMissionEligible === false) {
            throw new JourneyMissionIsNotEligibleException($missionType, $targetGalaxyPoint);
        }

        $journey = $this->journeyFactory->createJourney(
            $fleetTakingJourney->getId(),
            $missionType,
            $startGalaxyPoint,
            $targetGalaxyPoint,
            $this->journeyContext->calculateJourneyDuration(
                $fleetTakingJourney->getSpeed(),
                $startGalaxyPoint,
                $targetGalaxyPoint,
            ),
        );
        $fleetTakingJourney->startJourney($journey);

        $fuelRequirements = $this->journeyContext->calculateFuelRequirements(
            $startGalaxyPoint,
            $targetGalaxyPoint,
            $command->getShipsTakingJourney(),
        );

        $this->eventBus->dispatch(
            new FleetHasStartedJourneyEvent(
                $command->getPlanetId(),
                $fleetTakingJourney->getId()->getUuid(),
                $startGalaxyPoint->format(),
                $command->getTargetGalaxyPoint(),
                $fuelRequirements->toScalarArray(),
                $command->getResourcesLoad(),
            )
        );
    }

    private function isMissionEligible(
        FleetMissionType $missionType,
        GalaxyPointInterface $startGalaxyPoint,
        GalaxyPointInterface $targetGalaxyPoint,
        Fleet $fleet,
    ): bool {
        $galaxyMissionEligible = $this->$this->galaxyNavigator->isMissionEligible(
            $missionType,
            $startGalaxyPoint,
            $targetGalaxyPoint,
        );
        $fleetMissionEligible = $this->missionEligibilityChecker->isEligible(
            $missionType, $fleet,
        );

        return $galaxyMissionEligible && $fleetMissionEligible;
    }
}
