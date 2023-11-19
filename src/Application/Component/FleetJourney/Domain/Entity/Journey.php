<?php

declare(strict_types=1);

namespace TheGame\Application\Component\FleetJourney\Domain\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use TheGame\Application\Component\FleetJourney\Domain\MissionType;
use TheGame\Application\SharedKernel\Domain\GalaxyPointInterface;

class Journey
{
    private DateTimeInterface $startedAt;

    private DateTimeInterface $finishesAt;

    public function __construct(
        private MissionType $missionType,
        private GalaxyPointInterface $startPoint,
        private GalaxyPointInterface $targetPoint,
        private readonly int $duration,
    ) {
        $this->startedAt = new DateTimeImmutable();
        $this->finishesAt = new DateTimeImmutable(
            sprintf('+ %d seconds', $this->duration),
        );
    }

    public function getTargetGalaxyPoint(): GalaxyPointInterface
    {
        return $this->targetPoint;
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

    public function doesComeBack(): bool
    {
        return $this->missionType === MissionType::FlyBack;
    }

    public function comeBack(): void
    {
        $this->turnAround();
    }

    public function cancel(): void
    {
        $this->turnAround();
    }

    private function turnAround(): void
    {
        $startPoint = $this->startPoint;
        $this->startPoint = $this->targetPoint;
        $this->targetPoint = $startPoint;

        $this->missionType = MissionType::FlyBack;

        $this->finishesAt = new DateTimeImmutable(
            sprintf('+ %d seconds', $this->calculateTimeFromStart()),
        );
        $this->startedAt = new DateTimeImmutable();
    }

    private function calculateTimeFromStart(): int
    {
        $now = new DateTimeImmutable();

        return $now->getTimestamp() - $this->startedAt->getTimestamp();
    }

    public function isFinished(): bool
    {
        $now = new DateTimeImmutable();

        return $now->getTimestamp() >= $this->finishesAt->getTimestamp();
    }
}
