<?php

declare(strict_types=1);

namespace spec\TheGame\Application\SharedKernel\Domain;

use PhpSpec\ObjectBehavior;
use TheGame\Application\SharedKernel\Domain\EntityId\ResourceId;
use TheGame\Application\SharedKernel\Domain\ResourceAmount;

final class ResourcesSpec extends ObjectBehavior
{
    public function it_adds_new_resources(): void
    {
        $resourceId = new ResourceId("cbd7c53e-6e9c-426e-9298-6509316cdf2f");
        $resourceAmount = new ResourceAmount($resourceId, 500);

        $this->addResource($resourceAmount);

        $this->getAmount($resourceId)->shouldReturn(500);
    }

    public function it_adds_resources_but_really_append_existing_ones(): void
    {
        $resourceId = new ResourceId("cbd7c53e-6e9c-426e-9298-6509316cdf2f");
        $resourceAmount = new ResourceAmount($resourceId, 500);

        $this->addResource($resourceAmount);

        $nextResourceAmount = new ResourceAmount($resourceId, 750);
        $this->addResource($nextResourceAmount);

        $this->getAmount($resourceId)->shouldReturn(1250);
    }

    public function it_returns_zero_amount_when_doesnt_have_resource_registered(): void
    {
        $resourceId = new ResourceId("cbd7c53e-6e9c-426e-9298-6509316cdf2f");

        $this->getAmount($resourceId)->shouldReturn(0);
    }

    public function it_returns_scalar_array(): void
    {
        [$resourceAmount1, $resourceAmount2] = $this->addTwoResources();

        $this->toScalarArray()->shouldReturn([
            $resourceAmount1->getResourceId()->getUuid() => 500,
            $resourceAmount2->getResourceId()->getUuid() => 300,
        ]);
    }

    public function it_returns_all_resource_amounts(): void
    {
        [$resourceAmount1, $resourceAmount2] = $this->addTwoResources();

        $this->getAll()->shouldReturn([
            $resourceAmount1, $resourceAmount2,
        ]);
    }

    public function it_returns_resources_multiplied_by_the_number(): void
    {
        [$resourceAmount1, $resourceAmount2] = $this->addTwoResources();

        $multiplied = $this->multipliedBy(2);
        $multiplied->getAmount($resourceAmount1->getResourceId())->shouldReturn(1000);
        $multiplied->getAmount($resourceAmount2->getResourceId())->shouldReturn(600);
    }

    public function it_returns_sum_of_all_resources(): void
    {
        [$resourceAmount1, $resourceAmount2] = $this->addTwoResources();

        $this->sum()->shouldReturn(800);
    }

    private function addTwoResources(): array
    {
        $resourceId1 = new ResourceId("cbd7c53e-6e9c-426e-9298-6509316cdf2f");
        $resourceAmount1 = new ResourceAmount($resourceId1, 500);

        $resourceId2 = new ResourceId("f2732560-69d9-4b9f-91ec-68d0e0462ec6");
        $resourceAmount2 = new ResourceAmount($resourceId2, 300);

        $this->addResource($resourceAmount1);
        $this->addResource($resourceAmount2);

        return [$resourceAmount1, $resourceAmount2];
    }

    public function it_clears_registered_resources(): void
    {
        $resourceId = new ResourceId("cbd7c53e-6e9c-426e-9298-6509316cdf2f");
        $resourceAmount = new ResourceAmount($resourceId, 500);

        $this->addResource($resourceAmount);
        $this->clear();

        $this->getAmount($resourceId)->shouldReturn(0);
    }
}
