<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\FleetJourney\Command;

use PhpSpec\ObjectBehavior;

final class ReturnJourneysCommandSpec extends ObjectBehavior
{
    public function let(): void
    {
        $playerId = "0cfe1c65-2cac-4138-92bd-fac68027c39b";
        $this->beConstructedWith($playerId);
    }

    public function it_has_player_id(): void
    {
        $this->getPlayerId()->shouldReturn("0cfe1c65-2cac-4138-92bd-fac68027c39b");
    }
}
