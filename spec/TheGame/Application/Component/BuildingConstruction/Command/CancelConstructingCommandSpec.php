<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\BuildingConstruction\Command;

use PhpSpec\ObjectBehavior;

final class CancelConstructingCommandSpec extends ObjectBehavior
{
    public function let(): void
    {
        $planetId = "05FEB5A6-285B-46A8-8A3D-10280C68ECBA";
        $buildingId = "e3d6e563-ea57-4ccc-9456-58d8e6f9ff3d";

        $this->beConstructedWith(
            $planetId,
            $buildingId,
        );
    }

    public function it_has_planet_id(): void
    {
        $this->getPlanetId()->shouldReturn("05FEB5A6-285B-46A8-8A3D-10280C68ECBA");
    }

    public function it_has_building_id(): void
    {
        $this->getBuildingId()->shouldReturn("e3d6e563-ea57-4ccc-9456-58d8e6f9ff3d");
    }
}
