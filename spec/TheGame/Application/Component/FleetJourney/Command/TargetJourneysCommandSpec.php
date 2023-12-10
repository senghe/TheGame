<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\FleetJourney\Command;

use PhpSpec\ObjectBehavior;

final class TargetJourneysCommandSpec extends ObjectBehavior
{
    public function let(): void
    {
        $playerId = "c20f9c5c-09eb-4eba-bf7a-899507264f6a";
        $this->beConstructedWith($playerId);
    }

    public function it_has_player_id(): void
    {
        $this->getPlayerId()->shouldReturn("c20f9c5c-09eb-4eba-bf7a-899507264f6a");
    }
}
