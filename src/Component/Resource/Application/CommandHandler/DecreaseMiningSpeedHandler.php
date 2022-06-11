<?php

declare(strict_types=1);

namespace App\Component\Resource\Application\CommandHandler;

use App\Component\Resource\Application\Command\DecreaseMiningSpeedCommand;
use App\Component\Resource\Application\RootSnapshotProviderInterface;
use App\Component\Resource\Domain\AggregateInterface;
use App\Component\Resource\Domain\Enum\OperationType;
use App\Component\Resource\Port\PlanetRepositoryInterface;
use App\SharedKernel\Port\CommandHandlerInterface;
use App\SharedKernel\Port\TransactionalInterface;

final class DecreaseMiningSpeedHandler implements CommandHandlerInterface
{
    private AggregateInterface $aggregateRoot;

    private PlanetRepositoryInterface $planetRepository;

    private RootSnapshotProviderInterface $rootSnapshotProvider;

    private TransactionalInterface $transactionalContract;

    public function __construct(
        AggregateInterface $aggregateRoot,
        PlanetRepositoryInterface $planetRepository,
        RootSnapshotProviderInterface $rootSnapshotProvider,
        TransactionalInterface $transactionalContract
    ) {
        $this->aggregateRoot = $aggregateRoot;
        $this->planetRepository = $planetRepository;
        $this->rootSnapshotProvider = $rootSnapshotProvider;
        $this->transactionalContract = $transactionalContract;
    }

    public function __invoke(DecreaseMiningSpeedCommand $command): void
    {
        $planet = $this->planetRepository->findOneById($command->getPlanetId());
        $rootSnapshot = $this->rootSnapshotProvider->provide($planet);

        $this->aggregateRoot->setAggregateRoot($rootSnapshot);

        $this->transactionalContract->beginTransaction();
        $this->aggregateRoot->removeOperationsNotPerformedYet(OperationType::ChangeSpeed);
        $this->transactionalContract->commitTransaction();
    }
}