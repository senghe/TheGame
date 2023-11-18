<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\Shipyard\CommandHandler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TheGame\Application\Component\Shipyard\Command\CancelJobCommand;
use TheGame\Application\Component\Shipyard\Domain\Entity\Shipyard;
use TheGame\Application\Component\Shipyard\Domain\Event\JobHasBeenCancelledEvent;
use TheGame\Application\Component\Shipyard\Domain\JobId;
use TheGame\Application\Component\Shipyard\Domain\ShipyardId;
use TheGame\Application\Component\Shipyard\Exception\ShipyardHasNotBeenFoundException;
use TheGame\Application\Component\Shipyard\ShipyardRepositoryInterface;
use TheGame\Application\SharedKernel\Domain\PlanetId;
use TheGame\Application\SharedKernel\Domain\ResourcesInterface;
use TheGame\Application\SharedKernel\EventBusInterface;

final class CancelJobCommandHandlerSpec extends ObjectBehavior
{
    public function let(
        ShipyardRepositoryInterface $shipyardRepository,
        EventBusInterface $eventBus,
    ): void {
        $this->beConstructedWith($shipyardRepository, $eventBus);
    }

    public function it_throws_exception_when_shipyard_has_not_been_found(
        ShipyardRepositoryInterface $shipyardRepository
    ): void {
        $shipyardId = "3E303BDF-976A-4509-8611-A30D33781085";
        $jobId = "C4743117-1F59-449C-A023-7E0B00E670A4";

        $shipyardRepository->findAggregate(new ShipyardId($shipyardId))
            ->willReturn(null);

        $command = new CancelJobCommand($shipyardId, $jobId);
        $this->shouldThrow(ShipyardHasNotBeenFoundException::class)
            ->during('__invoke', [$command]);
    }

    public function it_cancels_job(
        ShipyardRepositoryInterface $shipyardRepository,
        Shipyard                    $shipyard,
        ResourcesInterface          $resourceRequirements,
        EventBusInterface           $eventBus,
    ): void {
        $shipyardId = "3E303BDF-976A-4509-8611-A30D33781085";
        $jobId = "C4743117-1F59-449C-A023-7E0B00E670A4";

        $shipyardRepository->findAggregate(new ShipyardId($shipyardId))
            ->willReturn($shipyard);

        $shipyard->cancelJob(new JobId($jobId))
            ->shouldBeCalledOnce();

        $shipyard->getResourceRequirements(
            new JobId($jobId)
        )->willReturn($resourceRequirements);

        $resourceRequirements->toScalarArray()
            ->willReturn([
                "09574CED-120F-43DE-9CC1-ADAA662E6A38" => 500,
                "51037306-C7B2-45E6-9EA2-E4B8874BA01E" => 350,
            ]);

        $planetId = "3577DD36-4D0C-4E57-88C3-135F8EF4ED1B";
        $shipyard->getPlanetId()->willReturn(new PlanetId($planetId));

        $eventBus->dispatch(Argument::type(JobHasBeenCancelledEvent::class))
            ->shouldBeCalledOnce();

        $command = new CancelJobCommand($shipyardId, $jobId);
        $this->__invoke($command);
    }
}
