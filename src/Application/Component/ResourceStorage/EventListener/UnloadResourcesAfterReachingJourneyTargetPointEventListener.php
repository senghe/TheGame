<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceStorage\EventListener;

use TheGame\Application\Component\FleetJourney\Domain\Event\FleetHasReachedJourneyTargetPointEvent;
use TheGame\Application\Component\Galaxy\Bridge\NavigatorInterface;
use TheGame\Application\Component\ResourceStorage\Command\DispatchResourcesCommand;
use TheGame\Application\SharedKernel\CommandBusInterface;
use TheGame\Application\SharedKernel\Domain\GalaxyPoint;
use TheGame\Application\SharedKernel\Domain\FleetMissionType;
use TheGame\Application\SharedKernel\Exception\InconsistentModelException;

final class UnloadResourcesAfterReachingJourneyTargetPointEventListener
{
    public function __construct(
        private readonly NavigatorInterface $navigator,
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function __invoke(FleetHasReachedJourneyTargetPointEvent $event): void
    {
        $mission = FleetMissionType::from($event->getMission());
        if ($mission !== FleetMissionType::Attack) {
            return;
        }

        $targetGalaxyPoint = GalaxyPoint::fromString($event->getTargetGalaxyPoint());
        $planetId = $this->navigator->getPlanetId($targetGalaxyPoint);
        if ($planetId === null) {
            throw new InconsistentModelException(sprintf("Planet %s has not been found", $planetId));
        }

        foreach ($event->getResourcesLoad() as $resourceId => $amount) {
            $command = new DispatchResourcesCommand(
                $planetId->getUuid(),
                $resourceId,
                $amount,
            );
            $this->commandBus->dispatch($command);
        }
    }
}
