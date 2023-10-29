<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceMiners\CommandHandler;

use TheGame\Application\Component\ResourceMiners\Command\ExtractResourcesCommand;
use TheGame\Application\Component\ResourceMiners\Domain\Event\ResourceHasBeenExtractedEvent;
use TheGame\Application\Component\ResourceMiners\ResourceMinersRepositoryInterface;
use TheGame\Application\SharedKernel\Domain\PlanetId;
use TheGame\Application\SharedKernel\EventBusInterface;
use TheGame\Application\SharedKernel\Exception\InconsistentModelException;

final class ExtractResourcesCommandHandler
{
    public function __construct(
        private readonly ResourceMinersRepositoryInterface $minersRepository,
        private readonly EventBusInterface $eventBus,
    ) {
    }

    public function __invoke(ExtractResourcesCommand $command): void
    {
        $planetId = new PlanetId($command->getPlanetId());
        $minersCollection = $this->minersRepository->findForPlanet($planetId);
        if ($minersCollection === null) {
            throw new InconsistentModelException(sprintf("Planet %d has no miners collection attached", $command->getPlanetId()));
        }

        if ($minersCollection->isEmpty()) {
            return;
        }

        $extractionResult = $minersCollection->extract();

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
