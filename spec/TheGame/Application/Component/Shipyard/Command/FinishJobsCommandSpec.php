<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\Shipyard\Command;

use PhpSpec\ObjectBehavior;

final class FinishJobsCommandSpec extends ObjectBehavior
{
    public function let(): void
    {
        $shipyardId = "79A575D4-C9C6-4432-9365-537A66DBA50C";

        $this->beConstructedWith($shipyardId);
    }

    public function it_has_shipyard_id(): void
    {
        $this->getShipyardId()->shouldReturn("79A575D4-C9C6-4432-9365-537A66DBA50C");
    }
}
