<?php

declare(strict_types=1);

namespace App\Component\Resource\Application\CommandHandler;

use App\Component\Resource\Application\Command\DecreaseMiningSpeedCommand;
use App\Component\Resource\Domain\AggregateRootInterface;
use App\Component\Resource\Domain\Enum\OperationType;
use App\Component\Resource\Port\PlanetRepositoryInterface;
use App\SharedKernel\Port\CommandHandlerInterface;

final class DecreaseMiningSpeedHandler implements CommandHandlerInterface
{
    private AggregateRootInterface $aggregateRoot;

    private PlanetRepositoryInterface $planetRepository;

    public function __construct(
        AggregateRootInterface $aggregateRoot,
        PlanetRepositoryInterface $planetRepository
    ) {
        $this->aggregateRoot = $aggregateRoot;
        $this->planetRepository = $planetRepository;
    }

    public function __invoke(DecreaseMiningSpeedCommand $command): void
    {
        $planet = $this->planetRepository->findOneById($command->getPlanetId());

        $this->aggregateRoot->build($planet);
        $this->aggregateRoot->removeOperationsNotPerformedYet(OperationType::ChangeSpeed);
    }
}