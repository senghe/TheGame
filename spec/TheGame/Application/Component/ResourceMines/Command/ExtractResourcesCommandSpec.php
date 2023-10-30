<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\ResourceMines\Command;

use PhpSpec\ObjectBehavior;
use TheGame\Application\SharedKernel\CommandInterface;

final class ExtractResourcesCommandSpec extends ObjectBehavior
{
    public function let(): void
    {
        $planetId = "d8391d08-7c2e-40d8-b60c-a7c982e5e19e";
        $resourceId = "b9d07968-c016-4485-9796-7c6f3a3ed2ed";
        $amount = 10;

        $this->beConstructedWith($planetId, $resourceId, $amount);
    }

    public function it_is_a_command(): void
    {
        $this->shouldImplement(CommandInterface::class);
    }

    public function it_has_planet_id(): void
    {
        $this->getPlanetId()->shouldReturn("d8391d08-7c2e-40d8-b60c-a7c982e5e19e");
    }
}
