<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\ResourceStorage\Bridge;

use PhpSpec\ObjectBehavior;
use TheGame\Application\Component\ResourceStorage\Domain\Entity\StoragesCollection;
use TheGame\Application\Component\ResourceStorage\ResourceStoragesRepositoryInterface;
use TheGame\Application\SharedKernel\Domain\PlanetId;
use TheGame\Application\SharedKernel\Domain\ResourceAmount;
use TheGame\Application\SharedKernel\Domain\ResourceId;
use TheGame\Application\SharedKernel\Domain\ResourceRequirements;
use TheGame\Application\SharedKernel\Exception\InconsistentModelException;

final class ResourceAvailabilityCheckerSpec extends ObjectBehavior
{
    public function let(
        ResourceStoragesRepositoryInterface $storagesRepository,
    ): void {
        $this->beConstructedWith($storagesRepository);
    }

    public function it_throws_exception_when_didnt_find_aggregate(
        ResourceStoragesRepositoryInterface $storagesRepository,
    ): void {
        $planetId = "19B1ABC2-8972-4730-8AEA-292AF303E39F";
        $resourceId = "3222B248-DB8A-4F1C-8F85-7EFAADCADAA0";

        $storagesRepository->findForPlanet(new PlanetId($planetId))
            ->willReturn(null);

        $requirements = new ResourceRequirements();
        $resourceAmount = new ResourceAmount(new ResourceId($resourceId), 5);
        $requirements->add($resourceAmount);

        $this->shouldThrow(InconsistentModelException::class)
            ->during('check', [
                new PlanetId($planetId),
                $requirements,
            ]);
    }

    public function it_returns_true_when_has_enough_resources(
        ResourceStoragesRepositoryInterface $storagesRepository,
        StoragesCollection $aggregate,
    ): void {
        $planetId = "19B1ABC2-8972-4730-8AEA-292AF303E39F";
        $resourceId = "3222B248-DB8A-4F1C-8F85-7EFAADCADAA0";

        $storagesRepository->findForPlanet(new PlanetId($planetId))
            ->willReturn($aggregate);

        $requirements = new ResourceRequirements();
        $resourceAmount = new ResourceAmount(new ResourceId($resourceId), 5);
        $requirements->add($resourceAmount);

        $aggregate->hasEnough($requirements)->willReturn(true);

        $this->check(new PlanetId($planetId), $requirements)
            ->shouldReturn(true);
    }

    public function it_returns_false_when_hasnt_enough_resources(
        ResourceStoragesRepositoryInterface $storagesRepository,
        StoragesCollection $aggregate,
    ): void {
        $planetId = "19B1ABC2-8972-4730-8AEA-292AF303E39F";
        $resourceId = "3222B248-DB8A-4F1C-8F85-7EFAADCADAA0";

        $storagesRepository->findForPlanet(new PlanetId($planetId))
            ->willReturn($aggregate);

        $requirements = new ResourceRequirements();
        $resourceAmount = new ResourceAmount(new ResourceId($resourceId), 5);
        $requirements->add($resourceAmount);

        $aggregate->hasEnough($requirements)->willReturn(false);

        $this->check(new PlanetId($planetId), $requirements)
            ->shouldReturn(false);
    }
}
