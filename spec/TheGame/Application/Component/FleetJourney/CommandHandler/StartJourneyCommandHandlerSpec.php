<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\FleetJourney\CommandHandler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TheGame\Application\Component\Balance\Bridge\FleetJourneyContextInterface;
use TheGame\Application\Component\FleetJourney\Command\StartJourneyCommand;
use TheGame\Application\Component\FleetJourney\Domain\Entity\Fleet;
use TheGame\Application\Component\FleetJourney\Domain\Entity\Journey;
use TheGame\Application\Component\FleetJourney\Domain\Event\FleetHasStartedJourneyEvent;
use TheGame\Application\Component\FleetJourney\Domain\Exception\CannotTakeJourneyToOutOfBoundGalaxyPointException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\JourneyMissionIsNotEligibleException;
use TheGame\Application\Component\FleetJourney\Domain\Factory\JourneyFactoryInterface;
use TheGame\Application\Component\FleetJourney\Domain\FleetIdInterface;
use TheGame\Application\Component\FleetJourney\Domain\MissionType;
use TheGame\Application\Component\FleetJourney\FleetResolverInterface;
use TheGame\Application\Component\Galaxy\Bridge\NavigatorInterface;
use TheGame\Application\SharedKernel\Domain\GalaxyPointInterface;
use TheGame\Application\SharedKernel\Domain\PlanetId;
use TheGame\Application\SharedKernel\Domain\ResourcesInterface;
use TheGame\Application\SharedKernel\EventBusInterface;

final class StartJourneyCommandHandlerSpec extends ObjectBehavior
{
    public function let(
        NavigatorInterface $galaxyNavigator,
        FleetResolverInterface $fleetResolver,
        JourneyFactoryInterface $journeyFactory,
        FleetJourneyContextInterface $journeyContext,
        EventBusInterface $eventBus,
    ): void {
        $this->beConstructedWith(
            $galaxyNavigator,
            $fleetResolver,
            $journeyFactory,
            $journeyContext,
            $eventBus,
        );
    }

    public function it_starts_journey(
        NavigatorInterface $galaxyNavigator,
        FleetResolverInterface $fleetResolver,
        JourneyFactoryInterface $journeyFactory,
        FleetJourneyContextInterface $journeyContext,
        EventBusInterface $eventBus,
        Fleet $fleetTakingJourney,
        GalaxyPointInterface $startGalaxyPoint,
        Journey $journey,
        FleetIdInterface $fleetId,
        ResourcesInterface $fuelRequirements,
    ): void {
        $planetId = "935b7ecd-739d-44b2-9c1d-51d7eaa6a937";
        $targetGalaxyPoint = "[1:2:3]";
        $missionType = "transport";
        $shipsTakingJourney = [
            "light-fighter" => 20,
        ];
        $resourcesLoad = [
            "f6f8ca08-39c7-4cca-ba95-ddda5735f7d4" => 400,
        ];

        $targetGalaxyPointStub = Argument::type(GalaxyPointInterface::class);
        $galaxyNavigator->isWithinBoundaries($targetGalaxyPointStub)
            ->willReturn(true);

        $resourcesLoadStub = Argument::type(ResourcesInterface::class);
        $fleetResolver->resolveFromPlanet(
            new PlanetId($planetId),
            $shipsTakingJourney,
            $resourcesLoadStub,
            $targetGalaxyPointStub,
        )->willReturn($fleetTakingJourney);

        $fleetSpeed = 30;
        $fleetId->getUuid()->willReturn("9db97f06-7154-4276-a99a-f82ba9e890ff");
        $fleetTakingJourney->getId()->willReturn($fleetId);
        $fleetTakingJourney->getStationingGalaxyPoint()->willReturn($startGalaxyPoint);
        $fleetTakingJourney->getId()->willReturn($fleetId);
        $fleetTakingJourney->getSpeed()->willReturn($fleetSpeed);

        $galaxyNavigator->isMissionEligible($missionType, $startGalaxyPoint, $targetGalaxyPointStub)
            ->willReturn(true);

        $journeyDuration = 50;
        $journeyContext->calculateJourneyDuration($fleetSpeed, $startGalaxyPoint, $targetGalaxyPointStub)
            ->willReturn($journeyDuration);

        $journeyFactory->createJourney(
            $fleetId,
            MissionType::Transport,
            $startGalaxyPoint,
            $targetGalaxyPointStub,
            $journeyDuration,
        )->willReturn($journey);

        $fleetTakingJourney->startJourney($journey)
            ->shouldBeCalledOnce();

        $journeyContext->calculateFuelRequirements($startGalaxyPoint, $targetGalaxyPointStub, $shipsTakingJourney)
            ->willReturn($fuelRequirements);

        $fuelRequirements->toScalarArray()
            ->willReturn([
                "059e1846-d36a-43b8-9e02-81dd552844ce" => 500,
            ]);

        $startGalaxyPoint->format()->willReturn("[1:2:3]");

        $eventBus->dispatch(Argument::type(FleetHasStartedJourneyEvent::class))
            ->shouldBeCalledOnce();

        $command = new StartJourneyCommand($planetId, $targetGalaxyPoint, $missionType, $shipsTakingJourney, $resourcesLoad);
        $this->__invoke($command);
    }

    public function it_throws_exception_when_target_point_is_not_in_galaxy_boundaries(
        NavigatorInterface $galaxyNavigator,
    ): void {
        $planetId = "935b7ecd-739d-44b2-9c1d-51d7eaa6a937";
        $targetGalaxyPoint = "[1:2:3]";
        $missionType = "transport";
        $shipsTakingJourney = [
            "light-fighter" => 20,
        ];
        $resourcesLoad = [
            "f6f8ca08-39c7-4cca-ba95-ddda5735f7d4" => 400,
        ];

        $targetGalaxyPointStub = Argument::type(GalaxyPointInterface::class);
        $galaxyNavigator->isWithinBoundaries($targetGalaxyPointStub)
            ->willReturn(false);

        $command = new StartJourneyCommand($planetId, $targetGalaxyPoint, $missionType, $shipsTakingJourney, $resourcesLoad);
        $this->shouldThrow(CannotTakeJourneyToOutOfBoundGalaxyPointException::class)
            ->during('__invoke', [$command]);
    }

    public function it_throws_exception_when_mission_is_not_eligible(
        NavigatorInterface $galaxyNavigator,
        FleetResolverInterface $fleetResolver,
        Fleet $fleetTakingJourney,
        GalaxyPointInterface $startGalaxyPoint,
        FleetIdInterface $fleetId,
    ): void {
        $planetId = "935b7ecd-739d-44b2-9c1d-51d7eaa6a937";
        $targetGalaxyPoint = "[1:2:3]";
        $missionType = "transport";
        $shipsTakingJourney = [
            "light-fighter" => 20,
        ];
        $resourcesLoad = [
            "f6f8ca08-39c7-4cca-ba95-ddda5735f7d4" => 400,
        ];

        $targetGalaxyPointStub = Argument::type(GalaxyPointInterface::class);
        $galaxyNavigator->isWithinBoundaries($targetGalaxyPointStub)
            ->willReturn(true);

        $resourcesLoadStub = Argument::type(ResourcesInterface::class);
        $fleetResolver->resolveFromPlanet(
            new PlanetId($planetId),
            $shipsTakingJourney,
            $resourcesLoadStub,
            $targetGalaxyPointStub,
        )->willReturn($fleetTakingJourney);

        $fleetId->getUuid()->willReturn("9db97f06-7154-4276-a99a-f82ba9e890ff");
        $fleetTakingJourney->getStationingGalaxyPoint()->willReturn($startGalaxyPoint);

        $galaxyNavigator->isMissionEligible($missionType, $startGalaxyPoint, $targetGalaxyPointStub)
            ->willReturn(false);

        $command = new StartJourneyCommand($planetId, $targetGalaxyPoint, $missionType, $shipsTakingJourney, $resourcesLoad);
        $this->shouldThrow(JourneyMissionIsNotEligibleException::class)
            ->during('__invoke', [$command]);
    }
}
