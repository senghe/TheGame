<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Entity\ResourceStoreInterface;

final class ResourceCountingTest extends IntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_counting(): void
    {
        $fixtures = $this->loadFixturesFromFile('ResourceCountingTest/test_counting.yaml');

        /** @var ResourceStoreInterface $resourceStore */
        $resourceStore = $fixtures['mineralStore'];

        $this->assertEquals(150, $resourceStore->getAmount());
    }
}