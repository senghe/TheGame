<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Resource\Domain\AggregateRootInterface;
use App\Resource\Domain\Entity\SnapshotInterface;
use App\Resource\Domain\ResourceStorageViewModelInterface;
use App\SharedKernel\Exception\AggregateRootNotBuiltException;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Tests\CodeBase\Repository\OperationRepository;
use Tests\CodeBase\Repository\ResourceRepository;
use Tests\CodeBase\Repository\SnapshotRepository;

final class ResourceAggregateRootTest extends IntegrationTestCase
{
    private AggregateRootInterface $resourcesAggregateRoot;

    private EntityManagerInterface $entityManager;

    private ResourceRepository $resourceRepository;

    private OperationRepository $operationRepository;

    private SnapshotRepository $snapshotRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resourcesAggregateRoot = $this->getContainer()->get('resource.domain.aggregate_root');
        $this->entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        $this->resourceRepository = $this->getContainer()->get('tests.repository.resource');
        $this->operationRepository = $this->getContainer()->get('tests.repository.operation');
        $this->snapshotRepository = $this->getContainer()->get('tests.repository.snapshot');
    }

    public function test_returning_resources_view_models(): void
    {
        $this->loadFixturesFromFile('ResourceAggregateRootTest/test_returning_resources_view_models.yaml');

        $this->resourcesAggregateRoot->build($this->resourceRepository->findAll());

        /** @var Collection<ResourceStorageViewModelInterface> */
        $models = $this->resourcesAggregateRoot->getResources();

        $this->assertCount(2, $models);

        $this->assertEquals('mineral', $models[0]->getCode());
        $this->assertEquals(400, $models[0]->getAmount());
        $this->assertEquals(false, $models[0]->isFull());

        $this->assertEquals('gas', $models[1]->getCode());
        $this->assertEquals(1800, $models[1]->getAmount());
        $this->assertEquals(false, $models[1]->isFull());
    }

    public function test_returning_resources_view_models_when_no_snapshot_is_available(): void
    {
        $this->loadFixturesFromFile('ResourceAggregateRootTest/test_returning_resources_view_models_when_no_snapshot_is_available.yaml');

        $this->resourcesAggregateRoot->build($this->resourceRepository->findAll());

        $models = $this->resourcesAggregateRoot->getResources();

        $this->assertCount(2, $models);

        $this->assertEquals('mineral', $models[0]->getCode());
        $this->assertEquals(250, $models[0]->getAmount());
        $this->assertEquals(false, $models[0]->isFull());

        $this->assertEquals('gas', $models[1]->getCode());
        $this->assertEquals(250, $models[1]->getAmount());
        $this->assertEquals(false, $models[1]->isFull());
    }

    public function test_returning_resources_on_non_built_aggregate(): void
    {
        $this->loadFixturesFromFile('ResourceAggregateRootTest/test_returning_resources_on_non_built_aggregate.yaml');

        $this->entityManager->clear();

        $this->expectException(AggregateRootNotBuiltException::class);

        $this->resourcesAggregateRoot->getResources();
    }

    public function test_performing_operation(): void
    {
        $this->loadFixturesFromFile('ResourceAggregateRootTest/test_performing_operation.yaml');

        $this->entityManager->clear();

        $this->resourcesAggregateRoot->build($this->resourceRepository->findAll());
        $this->resourcesAggregateRoot->performOperation(
            $this->operationRepository->findByCode('build-ship')
        );

        $this->entityManager->flush();

        $snapshots = $this->snapshotRepository->findAll();
        $this->assertCount(1, $snapshots);

        /** @var SnapshotInterface $snapshot */
        $snapshot = $snapshots->first();

        $operationsInSnapshot = $this->operationRepository->findBySnapshot($snapshot);
        $this->assertCount(2, $operationsInSnapshot);
    }

    public function test_performing_operation_on_closed_snapshot(): void
    {
        $this->loadFixturesFromFile('ResourceAggregateRootTest/test_performing_operation_on_closed_snapshot.yaml');

        $this->entityManager->clear();

        $this->resourcesAggregateRoot->build($this->resourceRepository->findAll());
        $this->resourcesAggregateRoot->performOperation(
            $this->operationRepository->findByCode('build-ship')
        );

        $this->entityManager->flush();

        $snapshots = $this->snapshotRepository->findAll();
        $this->assertCount(2, $snapshots);

        /** @var SnapshotInterface $snapshot */
        $tailSnapshot = $snapshots->first();
        $headSnapshot = $snapshots->last();

        $tailSnapshotOperations = $this->operationRepository->findBySnapshot($tailSnapshot);
        $this->assertCount(10, $tailSnapshotOperations);

        $headSnapshotOperations = $this->operationRepository->findBySnapshot($headSnapshot);
        $this->assertCount(1, $headSnapshotOperations);
    }

    public function test_performing_operation_when_no_snapshot_is_available(): void
    {
        $this->loadFixturesFromFile('ResourceAggregateRootTest/test_performing_operation_when_no_snapshot_is_available.yaml');

        $this->entityManager->clear();

        $this->resourcesAggregateRoot->build($this->resourceRepository->findAll());
        $this->resourcesAggregateRoot->performOperation(
            $this->operationRepository->findByCode('build-ship')
        );

        $this->entityManager->flush();

        $snapshots = $this->snapshotRepository->findAll();
        $this->assertCount(1, $snapshots);

        /** @var SnapshotInterface $snapshot */
        $snapshot = $snapshots->first();

        $operationsInSnapshot = $this->operationRepository->findBySnapshot($snapshot);
        $this->assertCount(1, $operationsInSnapshot);
    }

    public function test_performing_operation_on_non_built_aggregate(): void
    {
        $this->loadFixturesFromFile('ResourceAggregateRootTest/test_performing_operation_on_non_built_aggregate.yaml');

        $this->entityManager->clear();

        $this->expectException(AggregateRootNotBuiltException::class);

        $this->resourcesAggregateRoot->performOperation(
            $this->operationRepository->findByCode('build-ship')
        );
    }
}
