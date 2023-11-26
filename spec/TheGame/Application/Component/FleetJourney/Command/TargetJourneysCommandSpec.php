<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\FleetJourney\Command;

use PhpSpec\ObjectBehavior;

final class TargetJourneysCommandSpec extends ObjectBehavior
{
    public function let(): void
    {
        $userId = "c20f9c5c-09eb-4eba-bf7a-899507264f6a";
        $this->beConstructedWith($userId);
    }

    public function it_has_user_id(): void
    {
        $this->getUserId()->shouldReturn("c20f9c5c-09eb-4eba-bf7a-899507264f6a");
    }
}
