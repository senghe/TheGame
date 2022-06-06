<?php

declare(strict_types=1);

namespace App\Component\Resource\Application\CommandHandler;

use App\Component\Resource\Application\Command\IncreaseMiningSpeedCommand;
use App\Component\Resource\Domain\AggregateRootInterface;
use App\Component\Resource\Domain\Factory\OperationFactoryInterface;
use App\Component\Resource\Port\PlanetRepositoryInterface;
use App\Component\Resource\Port\ResourceRepositoryInterface;
use App\SharedKernel\Port\CommandHandlerInterface;

final class IncreaseMiningSpeedHandler implements CommandHandlerInterface
{
    private AggregateRootInterface $aggregateRoot;

    private OperationFactoryInterface $operationFactory;

    private ResourceRepositoryInterface $resourceRepository;

    private PlanetRepositoryInterface $planetRepository;

    public function __construct(
        AggregateRootInterface $aggregateRoot,
        OperationFactoryInterface $operationFactory,
        ResourceRepositoryInterface $resourceRepository,
        PlanetRepositoryInterface $planetRepository
    ) {
        $this->aggregateRoot = $aggregateRoot;
        $this->operationFactory = $operationFactory;
        $this->resourceRepository = $resourceRepository;
        $this->planetRepository = $planetRepository;
    }

    public function __invoke(IncreaseMiningSpeedCommand $command): void
    {
        $resource = $this->resourceRepository->findOneByCode($command->getResourceCode());
        $operation = $this->operationFactory->createSpeedChange($resource, $command->getSpeed());

        $planet = $this->planetRepository->findOneById($command->getPlanetId());

        $this->aggregateRoot->build($planet);
        $this->aggregateRoot->performOperation($operation);
    }
}