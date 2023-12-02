<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\Shipyard\Domain\ValueObject;

use PhpSpec\ObjectBehavior;
use TheGame\Application\Component\Shipyard\Domain\ConstructibleUnit;
use TheGame\Application\SharedKernel\Domain\ResourcesInterface;

final class CannonSpec extends ObjectBehavior
{
    public function let(
        ResourcesInterface $resourceRequirements,
    ): void {
        $type = 'laser';
        $duration = 500;
        $load = 15;

        $resourceRequirements->toScalarArray()->willReturn([
            "A287EB25-1F5A-44BB-9687-51879286949D" => 500,
            "CDBDFA7A-6243-41AF-84CE-18FD0A48D29F" => 250,
        ]);

        $this->beConstructedWith(
            $type,
            $resourceRequirements,
            $duration,
            $load
        );
    }

    public function it_has_cannon_construction_unit(): void
    {
        $this->getConstructionUnit()->shouldReturn(ConstructibleUnit::Cannon);
    }

    public function it_has_cannon_type(): void
    {
        $this->getType()->shouldReturn('laser');
    }

    public function it_has_resource_requirements(): void
    {
        $this->getRequirements()->toScalarArray()
            ->shouldReturn([
                "A287EB25-1F5A-44BB-9687-51879286949D" => 500,
                "CDBDFA7A-6243-41AF-84CE-18FD0A48D29F" => 250,
            ]);
    }

    public function it_has_construction_duration(): void
    {
        $this->getDuration()->shouldReturn(500);
    }

    public function it_has_production_load(): void
    {
        $this->getProductionLoad()->shouldReturn(15);
    }
}
