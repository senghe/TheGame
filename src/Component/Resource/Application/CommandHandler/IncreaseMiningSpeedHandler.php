<?php

declare(strict_types=1);

namespace App\Component\Resource\Application\CommandHandler;

use App\Component\Resource\Application\Command\IncreaseMiningSpeedCommand;
use App\Component\Resource\Application\RootSnapshotProviderInterface;
use App\Component\Resource\Domain\AggregateInterface;
use App\Component\Resource\Domain\Factory\OperationFactoryInterface;
use App\Component\Resource\Port\PlanetRepositoryInterface;
use App\Component\Resource\Port\ResourceRepositoryInterface;
use App\SharedKernel\Port\CommandHandlerInterface;
use App\SharedKernel\Port\TransactionalInterface;

final class IncreaseMiningSpeedHandler implements CommandHandlerInterface
{
    private AggregateInterface $aggregateRoot;

    private OperationFactoryInterface $operationFactory;

    private ResourceRepositoryInterface $resourceRepository;

    private PlanetRepositoryInterface $planetRepository;

    private RootSnapshotProviderInterface $rootSnapshotProvider;

    private TransactionalInterface $transactionalContract;

    public function __construct(
        AggregateInterface $aggregateRoot,
        OperationFactoryInterface $operationFactory,
        ResourceRepositoryInterface $resourceRepository,
        PlanetRepositoryInterface $planetRepository,
        RootSnapshotProviderInterface $rootSnapshotProvider,
        TransactionalInterface $transactionalContract
    ) {
        $this->aggregateRoot = $aggregateRoot;
        $this->operationFactory = $operationFactory;
        $this->resourceRepository = $resourceRepository;
        $this->planetRepository = $planetRepository;
        $this->rootSnapshotProvider = $rootSnapshotProvider;
        $this->transactionalContract = $transactionalContract;
    }

    public function __invoke(IncreaseMiningSpeedCommand $command): void
    {
        $resource = $this->resourceRepository->findOneByCode($command->getResourceCode());
        $operation = $this->operationFactory->createSpeedChange($resource, $command->getSpeed());

        $planet = $this->planetRepository->findOneById($command->getPlanetId());
        $rootSnapshot = $this->rootSnapshotProvider->provide($planet);

        $this->aggregateRoot->setAggregateRoot($rootSnapshot);

        $this->transactionalContract->beginTransaction();
        $this->aggregateRoot->performOperation($operation);
        $this->transactionalContract->commitTransaction();
    }
}