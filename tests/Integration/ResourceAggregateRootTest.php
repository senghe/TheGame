<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Domain\Resource\AggregateRootInterface;
use App\Domain\Resource\Entity\OperationInterface;
use App\Domain\Resource\Entity\SnapshotInterface;
use App\Domain\Resource\Exception\CannotPerformOperationException;
use App\Domain\Resource\ViewModel\ResourceStorageViewModelInterface;
use App\Domain\SharedKernel\Exception\AggregateRootNotBuiltException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
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

    public function test_building_aggregate_with_incorrect_resources_collection(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $notResourceMock = $this->createMock(OperationInterface::class);

        $this->act(function() use ($notResourceMock) {
            $this->resourcesAggregateRoot->build(new ArrayCollection([
                $notResourceMock
            ]));
        });
    }

    public function test_returning_resources_on_non_built_aggregate(): void
    {
        $this->expectException(AggregateRootNotBuiltException::class);

        $this->act(function() {
            $this->resourcesAggregateRoot->getResources();
        });
    }

    public function test_returning_resources_view_models(): void
    {
        $this->loadFixturesFromFile('ResourceAggregateRootTest/test_returning_resources_view_models.yaml');

        $models = new ArrayCollection();
        $this->act(function() use (&$models) {
            $this->resourcesAggregateRoot->build($this->resourceRepository->findAll());

            /** @var Collection<ResourceStorageViewModelInterface> */
            $models = $this->resourcesAggregateRoot->getResources();
        });

        $this->assertCount(2, $models);

        $this->assertEquals('mineral', $models[0]->getCode());
        $this->assertEquals(1550, $models[0]->getAmount());
        $this->assertEquals(false, $models[0]->isFull());

        $this->assertEquals('gas', $models[1]->getCode());
        $this->assertEquals(2600, $models[1]->getAmount());
        $this->assertEquals(false, $models[1]->isFull());
    }

    public function test_returning_resources_view_models_when_no_snapshot_is_available(): void
    {
        $this->loadFixturesFromFile('ResourceAggregateRootTest/test_returning_resources_view_models_when_no_snapshot_is_available.yaml');

        $this->act(function() use (&$models) {
            $this->resourcesAggregateRoot->build($this->resourceRepository->findAll());

            $models = $this->resourcesAggregateRoot->getResources();
        });

        $this->assertCount(2, $models);

        $this->assertEquals('mineral', $models[0]->getCode());
        $this->assertEquals(250, $models[0]->getAmount());
        $this->assertEquals(false, $models[0]->isFull());

        $this->assertEquals('gas', $models[1]->getCode());
        $this->assertEquals(250, $models[1]->getAmount());
        $this->assertEquals(false, $models[1]->isFull());
    }

    public function test_performing_operation_on_non_built_aggregate(): void
    {
        $this->expectException(AggregateRootNotBuiltException::class);

        $operationMock = $this->createMock(OperationInterface::class);

        $this->act(function() use ($operationMock) {
            $this->resourcesAggregateRoot->performOperation(
                $operationMock
            );
        });
    }

    public function test_performing_operation(): void
    {
        $this->loadFixturesFromFile('ResourceAggregateRootTest/test_performing_operation.yaml');

        $this->act(function() {
            $this->resourcesAggregateRoot->build($this->resourceRepository->findAll());
            $this->resourcesAggregateRoot->performOperation(
                $this->operationRepository->findByCode('build-ship')
            );

            $this->entityManager->flush();
        });

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

        $this->act(function() {
            $this->resourcesAggregateRoot->build($this->resourceRepository->findAll());
            $this->resourcesAggregateRoot->performOperation(
                $this->operationRepository->findByCode('build-ship')
            );

            $this->entityManager->flush();
        });

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

        $this->act(function() {
            $this->resourcesAggregateRoot->build($this->resourceRepository->findAll());
            $this->resourcesAggregateRoot->performOperation(
                $this->operationRepository->findByCode('build-ship')
            );

            $this->entityManager->flush();
        });

        $snapshots = $this->snapshotRepository->findAll();
        $this->assertCount(1, $snapshots);

        /** @var SnapshotInterface $snapshot */
        $snapshot = $snapshots->first();

        $operationsInSnapshot = $this->operationRepository->findBySnapshot($snapshot);
        $this->assertCount(1, $operationsInSnapshot);
    }

    public function test_performing_operation_with_too_less_resources_amount(): void
    {
        $this->loadFixturesFromFile('ResourceAggregateRootTest/test_performing_operation_with_too_less_resources_amount.yaml');

        $this->expectException(CannotPerformOperationException::class);

        $this->act(function() {
            $this->resourcesAggregateRoot->build($this->resourceRepository->findAll());

            $this->resourcesAggregateRoot->performOperation(
                $this->operationRepository->findByCode('build-ship')
            );
        });
    }

    public function test_performing_operation_with_much_resources_amount(): void
    {
        $this->loadFixturesFromFile('ResourceAggregateRootTest/test_performing_operation_with_much_resources_amount.yaml');

        $this->act(function() {
            $this->resourcesAggregateRoot->build($this->resourceRepository->findAll());

            $this->resourcesAggregateRoot->performOperation(
                $this->operationRepository->findByCode('fleet-came')
            );

            $this->entityManager->flush();
        });

        $snapshots = $this->snapshotRepository->findAll();

        /** @var ResourceStorageViewModelInterface $viewModel1 */
        $viewModel1 = $snapshots->first()->getResourcesViewModel()->get(0);
        $this->assertTrue($viewModel1->isFull());
        $this->assertEquals(2000, $viewModel1->getAmount());

        /** @var ResourceStorageViewModelInterface $viewModel2 */
        $viewModel2 = $snapshots->first()->getResourcesViewModel()->get(1);
        $this->assertTrue($viewModel2->isFull());
        $this->assertEquals(3000, $viewModel2->getAmount());
    }

    public function test_performing_operation_after_one_which_overflows_storage(): void
    {
        $this->loadFixturesFromFile('ResourceAggregateRootTest/test_performing_operation_after_one_which_overflows_storage.yaml');

        $this->act(function() {
            $this->resourcesAggregateRoot->build($this->resourceRepository->findAll());

            $this->resourcesAggregateRoot->performOperation(
                $this->operationRepository->findByCode('fleet-came')
            );

            $this->resourcesAggregateRoot->performOperation(
                $this->operationRepository->findByCode('build-ship')
            );

            $this->entityManager->flush();
        });

        $snapshots = $this->snapshotRepository->findAll();

        /** @var ResourceStorageViewModelInterface $viewModel1 */
        $viewModel1 = $snapshots->first()->getResourcesViewModel()->get(0);

        $this->assertFalse($viewModel1->isFull());
        $this->assertEquals(1850, $viewModel1->getAmount());

        /** @var ResourceStorageViewModelInterface $viewModel1 */
        $viewModel2 = $snapshots->first()->getResourcesViewModel()->get(1);

        $this->assertFalse($viewModel2->isFull());
        $this->assertEquals(2950, $viewModel2->getAmount());
    }
}
