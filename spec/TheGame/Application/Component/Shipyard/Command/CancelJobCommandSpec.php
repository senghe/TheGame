<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\Shipyard\Command;

use PhpSpec\ObjectBehavior;

final class CancelJobCommandSpec extends ObjectBehavior
{
    public function let(): void
    {
        $shipyardId = "E4330710-2AC5-4C1F-86B5-9332CEA8F91B";
        $jobId = "C3A52DA4-62C2-4E92-9C36-59D2AA04EEFB";

        $this->beConstructedWith($shipyardId, $jobId);
    }

    public function it_has_shipyard_id(): void
    {
        $this->getShipyardId()->shouldReturn("E4330710-2AC5-4C1F-86B5-9332CEA8F91B");
    }

    public function it_has_job_id(): void
    {
        $this->getJobId()->shouldReturn("C3A52DA4-62C2-4E92-9C36-59D2AA04EEFB");
    }
}
