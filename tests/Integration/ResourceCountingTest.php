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

        $amount = 0;
        $this->act(function() use ($resourceStorage, &$amount) {
            $amount = $resourceStorage->getAmount();
        });

        $this->assertEquals(150, $amount);
    }
}
