<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\Shipyard\Domain\Event;

use InvalidArgumentException;
use PhpSpec\ObjectBehavior;

final class NewCannonsHaveBeenQueuedEventSpec extends ObjectBehavior
{
    public function let(): void
    {
        $cannonType = 'laser';
        $quantity = 500;
        $planetId = "AD46097D-A5DB-457C-8B30-E19499DE5F6B";
        $resourceRequirements = [
            "1895B214-262B-4B97-AB16-5C2F848BF2F0" => 450,
            "352B0126-B1D5-4EC2-97A6-0C14480DBB76" => 420,
        ];

        $this->beConstructedWith(
            $cannonType,
            $quantity,
            $planetId,
            $resourceRequirements
        );
    }

    public function it_has_cannon_type(): void
    {
        $this->getType()->shouldReturn('laser');
    }

    public function it_has_quantity(): void
    {
        $this->getQuantity()->shouldReturn(500);
    }

    public function it_has_planet_id(): void
    {
        $this->getPlanetId()->shouldReturn("AD46097D-A5DB-457C-8B30-E19499DE5F6B");
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
        $cannonType = 'laser';
        $quantity = 500;
        $planetId = "AD46097D-A5DB-457C-8B30-E19499DE5F6B";
        $resourceRequirements = [
            125 => 450,
            150 => 420,
        ];

        $this->beConstructedWith(
            $cannonType,
            $quantity,
            $planetId,
            $resourceRequirements,
        );

        $this->shouldThrow(InvalidArgumentException::class)
            ->during('__construct', [
                $cannonType, $quantity, $planetId, $resourceRequirements,
            ]);
    }

    public function it_throws_exception_when_resource_requirements_is_an_array_with_no_integer_value(): void
    {
        $cannonType = 'laser';
        $quantity = 500;
        $planetId = "AD46097D-A5DB-457C-8B30-E19499DE5F6B";
        $resourceRequirements = [
            "1895B214-262B-4B97-AB16-5C2F848BF2F0" => 'test',
            "352B0126-B1D5-4EC2-97A6-0C14480DBB76" => 'test2',
        ];

        $this->beConstructedWith(
            $cannonType,
            $quantity,
            $planetId,
            $resourceRequirements,
        );

        $this->shouldThrow(InvalidArgumentException::class)
            ->during('__construct', [
                $cannonType, $quantity, $planetId, $resourceRequirements,
            ]);
    }
}
