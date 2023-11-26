<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\FleetJourney\Command;

use PhpSpec\ObjectBehavior;

final class CancelJourneyCommandSpec extends ObjectBehavior
{
    public function let(): void
    {
        $fleetId = "06d98267-6399-426f-be86-b79ea570046b";
        $this->beConstructedWith($fleetId);
    }

    public function it_has_fleet_id(): void
    {
        $this->getFleetId()->shouldReturn("06d98267-6399-426f-be86-b79ea570046b");
    }
}
