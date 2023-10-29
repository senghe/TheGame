<?php

declare(strict_types=1);

namespace spec\TheGame\Application\Component\ResourceMiners\CommandHandler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TheGame\Application\Component\ResourceMiners\Command\ExtractResourcesCommand;
use TheGame\Application\Component\ResourceMiners\Domain\Entity\MinesCollection;
use TheGame\Application\Component\ResourceMiners\Domain\Event\ResourceHasBeenExtractedEvent;
use TheGame\Application\Component\ResourceMiners\ResourceMinersRepositoryInterface;
use TheGame\Application\SharedKernel\Domain\PlanetId;
use TheGame\Application\SharedKernel\Domain\ResourceAmountInterface;
use TheGame\Application\SharedKernel\Domain\ResourceId;
use TheGame\Application\SharedKernel\EventBusInterface;
use TheGame\Application\SharedKernel\Exception\InconsistentModelException;

final class ExtractResourcesCommandHandlerSpec extends ObjectBehavior
{
    public function let(
        ResourceMinersRepositoryInterface $minersRepository,
        EventBusInterface $eventBus,
    ): void {
        $this->beConstructedWith($minersRepository, $eventBus);
    }

    public function it_extracts_two_kind_of_resources(
        ResourceMinersRepositoryInterface $minersRepository,
        EventBusInterface $eventBus,
        MinesCollection $minesCollection,
        ResourceAmountInterface $firstMineResourcesAmount,
        ResourceAmountInterface $secondMineResourcesAmount,
    ): void {
        $planetId = "777597c4-60e8-4043-a2dd-c52267a6eb9a";
        $command = new ExtractResourcesCommand($planetId);

        $minersRepository->findForPlanet(new PlanetId($planetId))
            ->willReturn($minesCollection);

        $minesCollection->isEmpty()->willReturn(false);

        $minesCollection->extract()->willReturn([
            $firstMineResourcesAmount->getWrappedObject(),
            $secondMineResourcesAmount->getWrappedObject(),
        ]);

        $firstResourceId = new ResourceId("6433bf14-907b-47c1-bcea-457cbdaa7b5b");
        $firstMineResourcesAmount->getResourceId()->willReturn($firstResourceId);
        $firstMineResourcesAmount->getAmount()->willReturn(10);

        $secondResourceId = new ResourceId("88a16f46-cb0b-4ffd-b6bd-cfbdbbde0f53");
        $secondMineResourcesAmount->getResourceId()->willReturn($secondResourceId);
        $secondMineResourcesAmount->getAmount()->willReturn(20);

        $eventBus->dispatch(Argument::type(ResourceHasBeenExtractedEvent::class))
            ->shouldBeCalled(2);

        $this->__invoke($command);
    }

    public function it_do_nothing_when_planet_has_no_mines(
        ResourceMinersRepositoryInterface $minersRepository,
        MinesCollection $minesCollection,
    ): void {
        $planetId = "777597c4-60e8-4043-a2dd-c52267a6eb9a";
        $command = new ExtractResourcesCommand($planetId);

        $minersRepository->findForPlanet(new PlanetId($planetId))
            ->willReturn($minesCollection);

        $minesCollection->isEmpty()->willReturn(true);

        $this->__invoke($command);
    }

    public function it_throws_exception_when_planet_has_no_mines_collection_which_is_obligatory(
        ResourceMinersRepositoryInterface $minersRepository,
    ): void {
        $planetId = "777597c4-60e8-4043-a2dd-c52267a6eb9a";
        $command = new ExtractResourcesCommand($planetId);

        $minersRepository->findForPlanet(new PlanetId($planetId))
            ->willReturn(null);

        $this->shouldThrow(InconsistentModelException::class)
            ->during('__invoke', [$command]);
    }
}
