<?php

declare(strict_types=1);

namespace spec\TheGame\Application\SharedKernel\Domain;

use PhpSpec\ObjectBehavior;
use TheGame\Application\SharedKernel\Domain\GalaxyPoint;

final class GalaxyPointSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith(1, 2, 3);
    }

    public function it_has_galaxy_number(): void
    {
        $this->getGalaxy()->shouldReturn(1);
    }

    public function it_has_solar_system_number(): void
    {
        $this->getSolarSystem()->shouldReturn(2);
    }

    public function it_has_planet_number(): void
    {
        $this->getPlanet()->shouldReturn(3);
    }

    public function it_formats_coordinates_to_string(): void
    {
        $this->format()->shouldReturn("[1:2:3]");
    }

    public function it_returns_array_of_coordinates(): void
    {
        $this->toArray()->shouldReturn([
            1, 2, 3,
        ]);
    }

    public function it_resolves_galaxy_point_from_string(): void
    {
        $resolvedPoint = GalaxyPoint::fromString("[1:2:3]");
        $this->object->shouldBeLike($resolvedPoint);
    }
}
