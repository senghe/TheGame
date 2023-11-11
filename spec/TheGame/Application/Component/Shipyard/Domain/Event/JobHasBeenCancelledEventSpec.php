<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\Shipyard\Domain\Event;

use InvalidArgumentException;
use PhpSpec\ObjectBehavior;

final class JobHasBeenCancelledEventSpec extends ObjectBehavior
{
    public function let(): void
    {
        $shipyardId = "916AF28B-5AA0-485F-8274-556EB11DA91B";
        $jobId = "B4E42EEB-C818-4FAC-935E-4972BABC75C9";
        $planetId = "9F9D2D0C-0954-49D1-B41C-C4456B2ACBBD";
        $resourceRequirements = [
            "1895B214-262B-4B97-AB16-5C2F848BF2F0" => 450,
            "352B0126-B1D5-4EC2-97A6-0C14480DBB76" => 420,
        ];

        $this->beConstructedWith(
            $shipyardId,
            $jobId,
            $planetId,
            $resourceRequirements,
        );
    }

    public function it_has_shipyard_id(): void
    {
        $this->getShipyardId()->shouldReturn("916AF28B-5AA0-485F-8274-556EB11DA91B");
    }

    public function it_has_job_id(): void
    {
        $this->getJobId()->shouldReturn("B4E42EEB-C818-4FAC-935E-4972BABC75C9");
    }

    public function it_has_planet_id(): void
    {
        $this->getPlanetId()->shouldReturn("9F9D2D0C-0954-49D1-B41C-C4456B2ACBBD");
    }

    public function it_has_resource_requirements(): void
    {
        $this->getResourceRequirements()->shouldReturn([
            "1895B214-262B-4B97-AB16-5C2F848BF2F0" => 450,
            "352B0126-B1D5-4EC2-97A6-0C14480DBB76" => 420,
        ]);
    }

    public function it_throws_exception_when_resource_requirements_is_an_array_with_no_string_keys(): void
    {
        $shipyardId = "916AF28B-5AA0-485F-8274-556EB11DA91B";
        $jobId = "B4E42EEB-C818-4FAC-935E-4972BABC75C9";
        $planetId = "9F9D2D0C-0954-49D1-B41C-C4456B2ACBBD";
        $resourceRequirements = [
            125 => 450,
            150 => 420,
        ];

        $this->beConstructedWith(
            $shipyardId,
            $jobId,
            $planetId,
            $resourceRequirements,
        );

        $this->shouldThrow(InvalidArgumentException::class)
            ->during('__construct', [
                $shipyardId, $jobId, $planetId, $resourceRequirements,
            ]);
    }

    public function it_throws_exception_when_resource_requirements_is_an_array_with_no_integer_value(): void
    {
        $shipyardId = "916AF28B-5AA0-485F-8274-556EB11DA91B";
        $jobId = "B4E42EEB-C818-4FAC-935E-4972BABC75C9";
        $planetId = "9F9D2D0C-0954-49D1-B41C-C4456B2ACBBD";
        $resourceRequirements = [
            "1895B214-262B-4B97-AB16-5C2F848BF2F0" => 'test',
            "352B0126-B1D5-4EC2-97A6-0C14480DBB76" => 'test2',
        ];

        $this->beConstructedWith(
            $shipyardId,
            $jobId,
            $planetId,
            $resourceRequirements,
        );

        $this->shouldThrow(InvalidArgumentException::class)
            ->during('__construct', [
                $shipyardId, $jobId, $planetId, $resourceRequirements,
            ]);
    }
}
