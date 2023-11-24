<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use TheGame\Application\Component\FleetJourney\Domain\Exception\CannotCancelFleetJourneyOnFlyBackException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\CannotCancelFleetJourneyOnReachingReturnPointException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\CannotCancelFleetJourneyOnReachingTargetPointException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\FleetHasNotYetReachedTheReturnPointException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\FleetHasNotYetReachedTheTargetPointException;
use TheGame\Application\Component\FleetJourney\Domain\Exception\FleetNotOnFlyBackException;
use TheGame\Application\Component\FleetJourney\Domain\FleetIdInterface;
use TheGame\Application\Component\FleetJourney\Domain\JourneyIdInterface;
use TheGame\Application\Component\FleetJourney\Domain\MissionType;
use TheGame\Application\SharedKernel\Domain\GalaxyPointInterface;

class Journey
{
    private DateTimeInterface $startedAt;

    private DateTimeInterface $plannedReachTargetAt;

    private DateTimeInterface $reachesTargetAt;

    private DateTimeInterface $plannedReturnAt;

    private DateTimeInterface $returnsAt;

    private GalaxyPointInterface $returnPoint;

    private bool $doesFlyBack = false;

    private bool $cancelled = false;

    public function __construct(
        private readonly JourneyIdInterface $journeyId,
        private readonly FleetIdInterface $fleetId,
        private readonly MissionType $missionType,
        private readonly GalaxyPointInterface $startPoint,
        private readonly GalaxyPointInterface $targetPoint,
        private readonly int $duration,
    ) {
        $this->startedAt = new DateTimeImmutable();
        $this->plannedReachTargetAt = new DateTimeImmutable();
        $this->plannedReturnAt = new DateTimeImmutable(
            sprintf('+ %d seconds', $this->duration),
        );
        $this->reachesTargetAt = $this->plannedReachTargetAt;
        $this->returnsAt = $this->plannedReturnAt;

        $this->returnPoint = $this->startPoint;
    }

    public function getId(): JourneyIdInterface
    {
        return $this->journeyId;
    }

    public function getMissionType(): MissionType
    {
        return $this->missionType;
    }

    public function getStartPoint(): GalaxyPointInterface
    {
        return $this->startPoint;
    }

    public function getTargetPoint(): GalaxyPointInterface
    {
        return $this->targetPoint;
    }

    public function getReturnPoint(): GalaxyPointInterface
    {
        return $this->returnPoint;
    }

    public function getStartedAt(): DateTimeInterface
    {
        return $this->startedAt;
    }

    public function getPlannedReachTargetAt(): DateTimeInterface
    {
        return $this->plannedReachTargetAt;
    }

    public function getReachesTargetAt(): DateTimeInterface
    {
        return $this->reachesTargetAt;
    }

    public function getPlannedReturnAt(): DateTimeInterface
    {
        return $this->plannedReturnAt;
    }

    public function getReturnsAt(): DateTimeInterface
    {
        return $this->returnsAt;
    }

    public function doesPlanToStationOnTarget(): bool
    {
        return $this->missionType === MissionType::Stationing;
    }

    public function doesAttack(): bool
    {
        return $this->missionType === MissionType::Attack;
    }

    public function doesTransportResources(): bool
    {
        return $this->missionType === MissionType::Transport;
    }

    public function doesFlyBack(): bool
    {
        return $this->doesFlyBack;
    }

    public function didReachTargetPoint(): bool
    {
        $now = new DateTimeImmutable();

        return $now >= $this->reachesTargetAt;
    }

    public function reachTargetPoint(): void
    {
        $now = new DateTimeImmutable();
        if ($this->didReachTargetPoint()) {
            $timeLeft = $this->reachesTargetAt->getTimestamp() - $now->getTimestamp();

            throw new FleetHasNotYetReachedTheTargetPointException($this->fleetId, $timeLeft);
        }

        if ($this->doesPlanToStationOnTarget()) {
            $this->returnPoint = $this->targetPoint;
            $this->returnsAt = $now;

            return;
        }

        $this->turnAround();
    }

    public function didReachReturnPoint(): bool
    {
        $now = new DateTimeImmutable();

        return $now >= $this->returnsAt;
    }

    public function reachReturnPoint(): void
    {
        if ($this->doesFlyBack() === false) {
            throw new FleetNotOnFlyBackException($this->fleetId);
        }

        $now = new DateTimeImmutable();
        if ($this->didReachReturnPoint() === false) {
            $timeLeft = $this->returnsAt->getTimestamp() - $now->getTimestamp();

            throw new FleetHasNotYetReachedTheReturnPointException($this->fleetId, $timeLeft);
        }

        $this->returnsAt = $now;
        $this->doesFlyBack = false;
    }

    public function isCancelled(): bool
    {
        return $this->cancelled;
    }

    public function cancel(): void
    {
        if ($this->doesFlyBack === true) {
            throw new CannotCancelFleetJourneyOnFlyBackException($this->fleetId);
        }

        if ($this->didReachTargetPoint() === true) {
            throw new CannotCancelFleetJourneyOnReachingTargetPointException($this->fleetId);
        }

        if ($this->didReachReturnPoint() === true) {
            throw new CannotCancelFleetJourneyOnReachingReturnPointException($this->fleetId);
        }

        $this->cancelled = true;
        $this->turnAround();
    }

    private function turnAround(): void
    {
        $this->doesFlyBack = true;

        $now = new DateTimeImmutable();
        $timeFromStart = $now->getTimestamp() - $this->startedAt->getTimestamp();

        $this->reachesTargetAt = $now;
        $this->returnsAt = new DateTimeImmutable(sprintf('+ %d seconds', $timeFromStart));
    }
}
