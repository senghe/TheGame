<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceMines\CommandHandler;

use TheGame\Application\Component\ResourceMines\Command\ExtractResourcesCommand;
use TheGame\Application\Component\ResourceMines\Domain\Event\ResourceHasBeenExtractedEvent;
use TheGame\Application\Component\ResourceMines\ResourceMinesRepositoryInterface;
use TheGame\Application\SharedKernel\Domain\EntityId\PlanetId;
use TheGame\Application\SharedKernel\EventBusInterface;
use TheGame\Application\SharedKernel\Exception\InconsistentModelException;

final class ExtractResourcesCommandHandler
{
    public function __construct(
        private readonly ResourceMinesRepositoryInterface $minesRepository,
        private readonly EventBusInterface $eventBus,
    ) {
    }

    public function __invoke(ExtractResourcesCommand $command): void
    {
        $planetId = new PlanetId($command->getPlanetId());
        $minesCollection = $this->minesRepository->findForPlanet($planetId);
        if ($minesCollection === null) {
            throw new InconsistentModelException(sprintf("Planet %d has no mines collection attached", $command->getPlanetId()));
        }

        if ($minesCollection->isEmpty()) {
            return;
        }

        $extractionResult = $minesCollection->extract();

        foreach ($extractionResult as $resourceAmount) {
            $event = new ResourceHasBeenExtractedEvent(
                $command->getPlanetId(),
                $resourceAmount->getResourceId()->getUuid(),
                $resourceAmount->getAmount(),
            );
            $this->eventBus->dispatch($event);
        }
    }
}
