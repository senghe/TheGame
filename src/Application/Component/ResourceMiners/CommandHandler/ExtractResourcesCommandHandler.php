<?php

declare(strict_types=1);

namespace TheGame\Application\Component\ResourceMiners\CommandHandler;

use TheGame\Application\Component\ResourceMiners\Command\ExtractResourcesCommand;
use TheGame\Application\Component\ResourceMiners\Domain\Event\ResourceHasBeenExtractedEvent;
use TheGame\Application\Component\ResourceMiners\ResourceMinersRepositoryInterface;
use TheGame\Application\SharedKernel\Domain\PlanetId;
use TheGame\Application\SharedKernel\EventBusInterface;

final class ExtractResourcesCommandHandler
{
    public function __construct(
        private readonly ResourceMinersRepositoryInterface $minersRepository,
        private readonly EventBusInterface $eventBus,
    ) {

    }

    public function __invoke(ExtractResourcesCommand $command): void
    {
        $planetId = new PlanetId($command->planetId);
        $minersCollection = $this->minersRepository->findForPlanet($planetId);
        $extractionResult = $minersCollection->extract();

        foreach ($extractionResult as $resourceAmount) {
            $event = new ResourceHasBeenExtractedEvent(
                $command->planetId,
                $resourceAmount->resourceId->getValue(),
                $resourceAmount->amount,
            );
            $this->eventBus->dispatch($event);
        }
    }
}
