<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\FleetJourney\Domain\Factory;

use DateTimeImmutable;
use PhpSpec\ObjectBehavior;
use TheGame\Application\Component\FleetJourney\Domain\Entity\Journey;
use TheGame\Application\Component\FleetJourney\Domain\FleetId;
use TheGame\Application\Component\FleetJourney\Domain\FleetIdInterface;
use TheGame\Application\Component\FleetJourney\Domain\JourneyId;
use TheGame\Application\Component\FleetJourney\Domain\MissionType;
use TheGame\Application\SharedKernel\Domain\GalaxyPoint;
use TheGame\Application\SharedKernel\UuidGeneratorInterface;

final class JourneyFactorySpec extends ObjectBehavior
{
    public function let(
        UuidGeneratorInterface $uuidGenerator,
    ): void {
        $this->beConstructedWith($uuidGenerator);
    }

    public function it_creates_journey(
        UuidGeneratorInterface $uuidGenerator,
    ): void {
        $journeyId = new JourneyId("443f9c57-b73a-4230-af53-8eaf2c33ac93");
        $uuidGenerator->generateNewJourneyId()->willReturn($journeyId);

        $fleetId = new FleetId("8804df77-de2d-46b2-b5f9-bffe9b695fd6");
        $startGalaxyPoint = new GalaxyPoint(1, 2, 3);
        $targetGalaxyPoint = new GalaxyPoint(4, 5, 6);
        $journeyDuration = 500;

        $createdJourney = $this->createJourney(
            $fleetId, MissionType::Transport, $startGalaxyPoint, $targetGalaxyPoint, $journeyDuration,
        );
        $createdJourney->shouldBeAnInstanceOf(Journey::class);
        $createdJourney->getStartPoint()->shouldReturn($startGalaxyPoint);
        $createdJourney->getTargetPoint()->shouldReturn($targetGalaxyPoint);

        $now = new DateTimeImmutable();
        $createdJourney->getPlannedReachTargetAt()->getTimestamp()->shouldReturn($now->getTimestamp()+500);
        $createdJourney->getPlannedReturnAt()->getTimestamp()->shouldReturn($now->getTimestamp()+1000);
    }
}
