<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\Shipyard\Domain\Entity;

use PhpSpec\ObjectBehavior;
use TheGame\Application\Component\Shipyard\Domain\ConstructibleUnit;
use TheGame\Application\Component\Shipyard\Domain\JobId;
use TheGame\Application\Component\Shipyard\Domain\ValueObject\Ship;
use TheGame\Application\SharedKernel\Domain\EntityId\ResourceId;
use TheGame\Application\SharedKernel\Domain\ResourceAmount;
use TheGame\Application\SharedKernel\Domain\Resources;

final class JobSpec extends ObjectBehavior
{
    public function let(): void
    {
        $jobId = "FF1CDE6A-FBE6-4C46-B2F5-5BD0C20B97F1";

        $constructibleType = 'light-fighter';
        $constructibleDuration = 500;
        $productionLoad = 75;

        $requirements = new Resources();
        $requirements->addResource(new ResourceAmount(
            new ResourceId("4B8CCD4D-6940-43F5-BFF5-A5FB35836294"),
            450,
        ));
        $requirements->addResource(new ResourceAmount(
            new ResourceId("A0F8E286-CA29-40FC-B33A-D1DCCEAA72D5"),
            220,
        ));
        $constructible = new Ship(
            $constructibleType,
            $requirements,
            $constructibleDuration,
            $productionLoad,
        );

        $quantity = 10;
        $this->beConstructedWith(
            new JobId($jobId),
            $constructible,
            $quantity
        );
    }

    public function it_has_identifier(): void
    {
        $this->getId()->shouldReturnAnInstanceOf(JobId::class);
        $this->getId()->getUuid()->shouldReturn("FF1CDE6A-FBE6-4C46-B2F5-5BD0C20B97F1");
    }

    public function it_has_construction_unit(): void
    {
        $this->getConstructionUnit()->shouldReturn(ConstructibleUnit::Ship);
    }

    public function it_has_type(): void
    {
        $this->getType()->shouldReturn('light-fighter');
    }

    public function it_has_construction_type(): void
    {
        $this->getConstructionType()->shouldReturn('light-fighter');
    }

    public function it_has_resource_requirements_for_the_job(): void
    {
        $this->getRequirements()->toScalarArray()->shouldReturn([
            "4B8CCD4D-6940-43F5-BFF5-A5FB35836294" => 4500,
            "A0F8E286-CA29-40FC-B33A-D1DCCEAA72D5" => 2200,
        ]);
    }

    public function it_has_quantity_of_units_to_be_constructed(): void
    {
        $this->getQuantity()->shouldReturn(10);
    }

    public function it_has_initial_quantity_of_units_to_be_constructed(): void
    {
        $this->getInitialQuantity()->shouldReturn(10);
        $this->finishPartially(500);

        $this->getInitialQuantity()->shouldReturn(10);
    }

    public function it_has_time_duration_of_the_current_job(): void
    {
        $this->getDuration()->shouldReturn(5000);
    }

    public function it_has_production_load_value(): void
    {
        $this->getProductionLoad()->shouldReturn(750);
    }

    public function it_finishes_job_partially(): void
    {
        $this->finishPartially(600);

        $this->getQuantity()->shouldReturn(9);
    }

    public function it_finishes_job_fully_instead_of_partially(): void
    {
        $this->finishPartially(5010);

        $this->getQuantity()->shouldReturn(0);
    }

    public function it_finishes_job_fully(): void
    {
        $this->finish();

        $this->getQuantity()->shouldReturn(0);
    }
}
