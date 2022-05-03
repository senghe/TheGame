<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Resource\Domain\Entity\StorageInterface;

final class ResourceCountingTest extends IntegrationTestCase
{
    public function test_counting(): void
    {
        $fixtures = $this->loadFixturesFromFile('ResourceCountingTest/test_counting.yaml');

        /** @var StorageInterface $resourceStorage */
        $resourceStorage = $fixtures['mineralStorage'];

        $this->assertEquals(150, $resourceStorage->getCurrentAmount());
    }
}
